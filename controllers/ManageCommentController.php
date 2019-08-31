<?php
class ManageCommentController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) < 2){
            throw new Exception("Page introuvable");
        } else {
            switch ($url[1]){
                //manage_comment/post-post_id=id
                case "post":
                    if(User::isConnected()) {
                        if (isset($_POST["content"]) && isset($params["post_id"])) {
                            $post = new Post((int)$params["post_id"]);
                            if ($post->exist()) {
                                $this->postComment($post, User::getConnectedUser()->getId(), $_POST["content"]);
                            }
                        }
                    }
                    break;
                //manage_comment/delete-comment_id=id
                case "delete":
                    if(User::isConnected()) {
                        if (isset($params["comment_id"])) {
                            $commentId = (int)$params["comment_id"];
                            if ($commentId > 0) {
                                $comment = new Comment($commentId);
                                if(User::getConnectedUser()->getId() == $comment->getAuthor() || User::getConnectedUser()->getRank() >= 1){
                                    $this->deleteComment($comment);
                                }
                            }
                        }
                    }
                    break;
                //manage_comment/edit-comment_id=id
                case "edit":
                    if(User::isConnected()){
                        if(isset($_POST["content"]) && isset($params["comment_id"])){
                            $commentId = (int)$params["comment_id"];
                            if($commentId > 0){
                                $comment = new Comment($commentId);
                                if(User::getConnectedUser()->getId() == $comment->getAuthor() || User::getConnectedUser()->getRank() >= 1){
                                    $this->editComment($comment, $_POST["content"]);
                                }
                            }
                        }
                    }
                    break;
            }
        }

    }

    private function postComment($post, $author, $content){
        $post->comment($author, $content);
        header("Location: /articles/".$post->getSlug());
    }

    private function deleteComment($comment){
        $comment->delete();
        header("Location: /articles/".$comment->getPost_id());
    }

    private function editComment($comment, $content){
        $comment->setContent($content);
        $comment->save();
        header("Location: /articles/".$comment->getPost_id());
    }


}

