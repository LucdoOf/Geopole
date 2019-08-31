<?php
class DisplayNotificationsController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(User::isConnected()){
                $this->displayNotifications();
            }
        }

    }

    private function displayNotifications(){
        require_once (APPLICATION_PATH."/views/displayers/display_notifications.php");
    }

}