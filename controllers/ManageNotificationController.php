<?php
class ManageNotificationController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(User::isConnected()){
                if(isset($_POST["notification_id"]) && isset($_POST["type"])){
                    $id = (int) $_POST["notification_id"];
                    $type = $_POST["type"];
                    if($id > 0 && is_string($type)){
                        $notification = new Notification($id);
                        if($notification->getTarget() == User::getConnectedUser()->getId() || User::getConnectedUser()->getRank() >= 1){
                            switch ($type){
                                case "seen":
                                    $notification->setSeen(true);
                                    $notification->save();
                                    break;
                                case "delete":
                                    $notification->delete();
                                    break;
                            }
                        }
                    }
                }
            }
        }

    }

}