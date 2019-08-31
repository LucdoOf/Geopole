<?php
class ContactController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 2){
            throw new Exception("Page introuvable");
        } else {
            if(count($url) == 1) {
                $sended = null;
                if(isset($params["success"])){
                    $sended = $params["success"] == "true" ? true : false;
                }
                $this->contact($url, $sended);
            } else {
                if($url[1] == "send"){
                    if(isset($_POST["mail"]) && isset($_POST["reason"]) && isset($_POST["content"]) && !empty($_POST["reason"]) && !empty($_POST["content"]) && filter_var($_POST["mail"], FILTER_VALIDATE_EMAIL)) {
                        $result = Mailer::sendMail($_POST["mail"], "contact-success.html", [], "Votre message nous est bien parvenu");
                        $result2 = Mailer::sendMail(Mailer::CONTACT_MAIL, "contact-content.html", ["reason" => $_POST["reason"], "contact-content" => $_POST["content"], "mail" => $_POST["mail"]], "Contact de " . $_POST["mail"] . " via le formulaire", $_POST["mail"]);
                        header("Location: " . "/contact?success=" . ($result & $result2 ? "true" : "false"));
                    } else {
                        header("Location: " . "/contact?success=false");
                    }
                }
            }
        }

    }

    private function contact($url, $sended){
        require_once(APPLICATION_PATH . "/views/contact.php");
    }


}