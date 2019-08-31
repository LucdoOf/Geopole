<?php
class HomeController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            $this->home($url);
        }

    }

    private function home($url){
        require_once(APPLICATION_PATH . "/views/home.php");
    }


}