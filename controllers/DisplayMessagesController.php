<?php
class DisplayMessagesController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(isset($_POST["foreigner"])){
                $page = isset($_POST["page"]) ? (int)$_POST["page"] : 0;
                $this->displayConversation($url, $_POST["foreigner"], $page);
            } else {
                $this->displayConversations($url);
            }
        }

    }

    private function displayConversation($url, $foreignUser, $page){
        require_once (APPLICATION_PATH."/views/displayers/display_messages.php");
    }

    private function displayConversations($url){
        require_once (APPLICATION_PATH."/views/displayers/display_messages.php");
    }

}