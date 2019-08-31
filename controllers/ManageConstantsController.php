<?php
class ManageConstantsController
{

    public function __construct($url, $params)
    {
        if((isset($url) && count($url) > 1) || (isset($params) && count($params) > 0)){
            throw new Exception("Page introuvable");
        } else {
            if(!User::isConnected() || User::getConnectedUser()->getRank() < 2){
                header("/home");
            } else {
                if(isset($_POST["type"])){
                    if(isset($_POST["id"])){
                        $id = (int) $_POST["id"];
                        if($id > 0){
                            switch ($_POST["type"]){
                                case 'category':
                                    $category = new Category($id);
                                    $category->delete();
                                    break;
                            }
                        }
                    } else if(isset($_POST["name"])){
                        if(is_string($_POST["name"])){
                            switch ($_POST["type"]) {
                                case 'category':
                                    $category = new Category(["name" => $_POST["name"]]);
                                    $category->save();
                                    break;
                            }
                        }
                    }
                } else {
                    $this->manageConstants($url);
                }
            }
        }
    }

    private function manageConstants($url){
        require APPLICATION_PATH."/views/dashboard/dashboard_manage_constants.php";
    }

}