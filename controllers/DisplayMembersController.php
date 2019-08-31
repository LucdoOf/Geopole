<?php
class DisplayMembersController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(isset($_POST["firstLetters"]) && isset($_POST["page"])){
                $this->displayMembers($_POST["firstLetters"], $_POST["page"]);
            }
        }

    }

    private function displayMembers($firstLetters, $page){
        require_once (APPLICATION_PATH."/views/displayers/display_members.php");
    }

}