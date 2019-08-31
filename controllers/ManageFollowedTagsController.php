<?php
class ManageFollowedTagsController
{

    public function __construct($url, $params)
    {
        if((isset($url) && count($url) > 1) || (isset($params) && count($params) > 0)){
            throw new Exception("Page introuvable");
        } else {
            if(!User::isConnected()){
                header("/home");
            } else {
                if(isset($_POST["type"])){
                    if(isset($_POST["id"])){
                        $id = (int) $_POST["id"];
                        if($id > 0){
                            switch ($_POST["type"]){
                                case 'category':
                                    User::getConnectedUser()->unfollowCategory($id);
                                    break;
                            }
                        }
                    } else if(isset($_POST["name"])){
                        if(is_string($_POST["name"])){
                            switch ($_POST["type"]) {
                                case 'category':
                                    $category = Category::getByName($_POST["name"]);
                                    if($category->exist()) {
                                        User::getConnectedUser()->followCategory($category);
                                    }
                                    break;
                            }
                        }
                    }
                }
            }
        }
    }
}