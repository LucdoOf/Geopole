<?php
class SendDataController
{
    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 1){
            throw new Exception("Page introuvable");
        } else {
            if(isset($_POST["data"]) && isset($_POST["type"])){
                $this->sendData();
            }
        }
    }

    private function sendData(){
        require_once (APPLICATION_PATH."/views/utils/send_data.php");
    }
}