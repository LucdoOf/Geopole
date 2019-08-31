<?php
class RatePostController
{

    public function __construct($url, $params)
    {
        if((isset($url) && count($url) > 1) || (isset($params) && count($params) > 0)){
            throw new Exception("Page introuvable");
        } else {
            if(isset($_POST["post_id"]) && isset($_POST["rating"])){
                if(User::isConnected()){
                    $id = (int) $_POST["post_id"];
                    $rating = (int) $_POST["rating"];
                    if($id >= 0 && $rating >= 0){
                        $post = new Post($id);
                        $user = User::getConnectedUser();
                        $user->ratePost($post, $rating);
                    }
                }
            }
        }
    }

}