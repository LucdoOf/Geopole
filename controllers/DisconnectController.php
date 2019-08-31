<?php
class DisconnectController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(User::isConnected()){
                User::disconnect();
                header("Location: /home");
            } else {
                throw new Exception("Vous n'êtes pas connecté");
            }
        }

    }

}