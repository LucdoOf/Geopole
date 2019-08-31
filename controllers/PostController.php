<?php
class PostController
{
    public function __construct($url, $params)
    {
        if((isset($url) && count($url) < 2)){
            throw new Exception("Page introuvable");
        } else {
            if(file_exists(APPLICATION_PATH . "/post/".$url[1].".php")){
                require APPLICATION_PATH . "/post/".$url[1].".php";
            } else {
                throw new Exception("Page introuvable (2)");
            }
        }
    }

}