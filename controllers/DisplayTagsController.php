<?php
class DisplayTagsController
{

    public function __construct($url, $params)
    {
        if((isset($url) && count($url) > 1)){
            throw new Exception("Page introuvable");
        } else {
            if(isset($params)){
                switch ($params["type"]) {
                    case "categories":
                        $this->displayCategories();
                        break;
                    case "followed_categories":
                        if(!User::isConnected()) return;
                        $this->displayFollowedCategories(User::getConnectedUser());
                        break;
                }
            }
        }
    }

    private function displayCategories(){
        $categories = Category::getAll();
        require APPLICATION_PATH."/views/displayers/display_tags.php";
    }

    private function displayFollowedCategories($user){
        $categories = $user->getFollowedCategories();
        require APPLICATION_PATH."/views/displayers/display_tags.php";
    }

}