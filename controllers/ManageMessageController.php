<?php
class ManageMessageController
{

    public function __construct($url, $params)
    {
        if ((isset($url) && count($url) > 1) || (isset($params) && count($params) > 0)) {
            throw new Exception("Page introuvable");
        } else {
            if(!isset($_POST["type"])) return;
            if(!User::isConnected()) return;
            switch ($_POST["type"]){
                case "send":
                    if(isset($_POST["foreigner"])){
                        if((int)$_POST["foreigner"] >= 0) {
                            $foreignerUser = new User((int)$_POST["foreigner"]);
                            if($foreignerUser->exist()) {
                                if (isset($_POST["content"])) {
                                    User::getConnectedUser()->sendMessage($foreignerUser, $_POST["content"]);
                                }
                            }
                        }
                    }
                    break;
                case "see":
                    if(isset($_POST["foreigner"])){
                        if((int)$_POST["foreigner"] >= 0) {
                            Message::seeMessages((int)$_POST["foreigner"], User::getConnectedUser()->getId());
                        }
                    }
                    break;
            }
        }
    }

}