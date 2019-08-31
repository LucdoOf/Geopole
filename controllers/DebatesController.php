<?php
class DebatesController
{

    const RESPONSES_BY_PAGE = 10;

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) == 1) {
            $this->debates();
        } else if(count($url) == 2 || count($url) == 3 || count($url) == 4){
            if($url[1] == "create" && count($url) == 2){
                if(isset($_POST["title"]) && isset($_POST["description"]) && isset($_POST["cover"]) && !empty($_POST["title"]) && !empty($_POST["description"]) && !empty($_POST["cover"])){
                    if(User::isConnected()) {
                        $user = User::getConnectedUser();
                        $slug = Slugifier::slugify($_POST["title"]);
                        $debate = Debate::getBySlug($slug);
                        if(!$debate->exist()) {
                            Sitemap::generate();
                            $debate = new Debate();
                            $debate->setTitle($_POST["title"]);
                            $debate->setAuthor(User::getConnectedUser()->getId());
                            $debate->setDescription($_POST["description"]);
                            $debate->setImage($_POST["cover"]);
                            $debate->setSlug($slug);
                            $debate->setPending($user->getRank()<=1 ? 1 : 0);
                            $debate->save();
                            if (isset($_POST["options"]) && !empty($_POST["options"])) {
                                $options = explode(";", $_POST["options"]);
                                foreach ($options as $option) {
                                    $debate->addOption($option);
                                }
                            }
                            header("Location: /debates/" . $debate->getSlug());
                        } else {
                            $this->create("Un débat similaire existe déjà");
                        }
                    } else {
                        $this->create("Connectez vous pour créer un débat");
                    }
                } else {
                    $error = null;
                    if(isset($_POST["cover"]) && empty($_POST["cover"])) $error = "Veuillez choisir une image";
                    if(isset($_POST["description"]) && empty($_POST["description"])) $error = "Veuillez rentrez une description";
                    if(isset($_POST["title"]) && empty($_POST["title"])) $error = "Veuillez rentrez un titre";
                    $this->create($error);
                }
            } else if(count($url) == 3 || count($url) == 2 || count($url) == 4){
                $debate = Debate::getBySlug($url[1]);
                if($debate->exist() && !empty($url[1])) {
                    if ($debate->getPending() == 0) {
                        if (count($url) == 3 && $url[2] === "answer") {
                            if (User::isConnected()) {
                                if (isset($_POST["title"]) && isset($_POST["content"]) && isset($_POST["option"])) {
                                    $debate->reply($_POST["content"], $_POST["title"], $_POST["option"], User::getConnectedUser()->getId());
                                    header("Location: /debates/" . $debate->getSlug());
                                } else if (isset($_POST["title"]) && isset($_POST["content"])) {
                                    $options = $debate->getOptions();
                                    if (empty($options)) {
                                        $debate->reply($_POST["content"], $_POST["title"], -1, User::getConnectedUser()->getId());
                                        header("Location: /debates/" . $debate->getSlug());
                                    }
                                }
                            }
                        } else if(count($url) == 2){
                            $page = isset($params["page"]) ? $params["page"] : 0;
                            $this->debate($debate, $page);
                        } else if(count($url) == 4) {
                            if(User::isConnected()) {
                                $connectedUser = User::getConnectedUser();
                                $response = new DebateResponse($url[3]);
                                if ($response->exist()) {
                                    if ($url[2] == "reply") {
                                        if(isset($_POST["content"]) && !empty($_POST["content"])) {
                                            $parent = $response;
                                            if ($response->getParent_id() > 0) {
                                                $parent = new DebateResponse($response->getParent_id());
                                            }
                                            Notifier::debateSubResponse($response->getAuthor(), $connectedUser->getPseudo(), $_POST["content"], $debate->getSlug());
                                            if (Str::startsWith($_POST["content"], "#")) {
                                                $users = Str::extractUsersFromString($_POST["content"]);
                                                if (!empty($users) && $users[0]->getId() != $response->getAuthor()) {
                                                    Notifier::debateResponseMention($response->getAuthor(), $connectedUser->getPseudo(), $_POST["content"], $debate->getSlug());
                                                }
                                            }
                                            $parent->subReply($_POST["content"], $connectedUser->getId());
                                            header("Location: /debates/".$debate->getSlug());
                                        } else {
                                            throw new Exception("Rentrez un message valide");
                                        }
                                    } else if ($url[2] == "react") {
                                        if(isset($_POST["action"]) && !empty($_POST["action"])){
                                            if(isset($_POST["remove"]) && $_POST["remove"] == "true"){
                                                $response->clearReactions($connectedUser);
                                            } else {
                                                if($_POST["action"] == "like" && $connectedUser->getId() != $debate->getAuthor()) {
                                                    Notifier::debateResponseLiked($debate->getAuthor(), $connectedUser->getPseudo(), $debate->getSlug());
                                                }
                                                $response->react($connectedUser, $_POST["action"]);
                                            }
                                        } else {
                                            throw new Exception("Action invalide");
                                        }
                                    } else {
                                        throw new Exception("Page introuvable (2)");
                                    }
                                } else {
                                    throw new Exception("Commentaire introuvable", 404);
                                }
                            } else {
                                throw new Exception("Vous devez être connecté", 400);
                            }
                        }
                    } else {
                        throw new Exception("Ce débat est en attente de confirmation");
                    }
                } else {
                    throw new Exception("Ce débat n'existe pas", 404);
                }
            }
        }
    }

    private function debate($debate,$page){
        $debateOptions = $debate->getOptions();
        $debateResponses = $debate->getHeadingResponses(DebatesController::RESPONSES_BY_PAGE, $page*DebatesController::RESPONSES_BY_PAGE);
        $counter = $debate->countResponses();
        require_once(APPLICATION_PATH."/views/debate.php");
    }

    private function debates(){
        $pinnedDebates = Debate::getPinneds();
        $debates = Debate::getActives();
        require_once(APPLICATION_PATH."/views/debates.php");
    }

    private function create($error){
        require_once(APPLICATION_PATH."/views/create-debate.php");
    }


}