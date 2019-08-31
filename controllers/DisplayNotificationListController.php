<?php
class DisplayNotificationListController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(isset($_POST["page"])){
                if(User::isConnected()) {
                    $this->displayNotifications($_POST["page"]);
                }
            }
        }

    }

    private function displayNotifications($page){
        require_once (APPLICATION_PATH."/views/displayers/display_notification_list.php");
    }

}