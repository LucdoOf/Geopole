<?php
	switch ($_POST["data"]) {
		case 'post':
			if(isset($_POST["post_id"]) && (int) $_POST["post_id"] >= 0){
				$post = new Post($_POST["post_id"]);
				switch ($_POST["type"]) {
					case 'description':
						echo $post->getDescription();
						break;
					case 'content':
						echo $post->getContent();
						break;
					case 'title':
						echo $post->getTitle();
						break;
                    case "slug":
                        echo $post->getSlug();
                        break;
					case "categories":
						$toEcho = "";
						$categories = $post->getCategories();
						foreach ($categories as $key => $category) {
							if(!empty($category)){
								$toEcho = $toEcho . $category->getName() . ",";
							}
						}
						echo $toEcho;
						break;
                    case "image":
                        echo $post->getImage();
                        break;
				}
			}
			break;
        case 'notification':
            switch ($_POST["type"]){
                case 'list':
                    if(User::isConnected()){
                        $toEcho = "";
                        $notifications = User::getConnectedUser()->getNotifications();
                        foreach ($notifications as $notification){
                            $toEcho = $toEcho . $notification->getId() . ";";
                        }
                        echo substr($toEcho, 0, strlen($toEcho)-1); //Pour enlever le ; final
                    }
                    break;
                case 'count':
                    if(User::isConnected()){
                        $toEcho = 0;
                        $notifications = User::getConnectedUser()->getNotifications();
                        foreach ($notifications as $notification){
                            if(!$notification->getSeen()){
                                $toEcho++;
                            }
                        }
                        echo $toEcho;
                    }
                    break;
            }
            break;
        case "message":
        	if(!User::isConnected()) return;
        	switch($_POST["type"]){
        		case "check_message":
        			if(isset($_POST["foreigner"])){
        				$foreigner = (int) $_POST["foreigner"];
        				if($foreigner >= 0){
	        				$messages = User::getConnectedUser()->getMessageMap()[$foreigner];
	        				echo $messages[count($messages)-1]->getId();
        				}
        			}
	        		break;
                case "check_conversations":
                    $messageMap = User::getConnectedUser()->getMessageMap();
                    $toEcho = "";
                    foreach($messageMap as $foreigner => $messages){
                        $toEcho = $toEcho.$foreigner.":".$messages[count($messages)-1]->getId().";";
                    }
                    echo substr($toEcho, 0, -1); //Pour enlever le dernier ;
                    break;
        	}
        	break;
        case "parser":
            switch ($_POST["type"]){
                case "debate_response":
                    if(isset($_POST["id"])){
                        $response = new DebateResponse($_POST["id"]);
                        $subResponses = $response->getSubResponses();
                        DebateParser::parseResponse($response, $subResponses);
                    }
                    break;
                case "post_comment":
                    if(isset($_POST["id"])){
                        $comment = new Comment($_POST["id"]);
                        $subComments = $comment->getSubComments();
                        ArticleParser::parseComment(new Comment($_POST["id"]), $subComments);
                    }
                    break;
            }
            break;
}


