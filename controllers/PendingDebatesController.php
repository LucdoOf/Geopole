<?php
class PendingDebatesController
{

    public function __construct($url, $params)
    {
        if (isset($url) && count($url) > 3) {
            throw new Exception("Page introuvable");
        } else {
            if (!User::isConnected() || User::getConnectedUser()->getRank() < 2) {
                header("Location: /home");
            } else {
                if(count($url) == 1) {
                    $this->pendingDebates($url);
                } else {
                    $debate = new Debate($url[1]);
                    if($debate->exist()){
                        if($debate->getPending() == 1){
                            if(count($url) == 2) {
                                $this->pendingDebate($url, $debate);
                            } else if($url[2] == "treat"){
                                if(isset($_POST["cancel-text"])){
                                    $debate->delete();
                                    $notification = new Notification();
                                    $notification->setTarget($debate->getAuthor());
                                    $notification->setName("Votre débat n'a pas été retenu");
                                    $notification->setContent($_POST["cancel-text"]);
                                    $notification->setHref("/dashboard/notifications");
                                    $notification->setType("debate-refuse");
                                    $notification->setMetadata("");
                                    $notification->save();
                                    header("Location: /pending_debates");
                                } else {
                                    if(!empty($_POST["options"])) {
                                        $debate->cleanOptions();
                                        foreach (explode(";", $_POST["options"]) as $option) {
                                            $debate->addOption($option);
                                        }
                                    }
                                    $debate->setTitle($_POST["title"]);
                                    $debate->setImage($_POST["cover"]);
                                    $debate->setSlug($_POST["slug"]);
                                    $debate->setDescription($_POST["description"]);
                                    $debate->setPending(0);
                                    $debate->save();
                                    Sitemap::generate();
                                    $notification = new Notification();
                                    $notification->setTarget($debate->getAuthor());
                                    $notification->setName("Votre débat a été accepté, fellicitations !");
                                    $notification->setContent("Votre débat " . $debate->getTitle() . " a été retenu par nos équipes ! Vous pouvez déjà le consulter en cliquant ici");
                                    $notification->setHref("/debates/".$debate->getSlug());
                                    $notification->setType("debate-accept");
                                    $notification->setMetadata("");
                                    $notification->save();
                                    header("Location: /pending_debates");
                                }
                            } else {
                                throw new Exception("Page introuvable (2)");
                            }
                        } else {
                            throw new Exception("Ce débat n'est plus en attente");
                        }
                    } else {
                        throw new Exception("Débat introuvable");
                    }
                }
            }
        }
    }

    public function pendingDebates($url){
        $debates = Debate::getPendings();
        require_once (APPLICATION_PATH."/views/dashboard/dashboard_pending_debates.php");
    }

    public function pendingDebate($url, $debate){
        $options = $debate->getOptions();
        $authorObj = new User($debate->getAuthor());
        $author = $authorObj->getPseudo();
        require_once (APPLICATION_PATH."/views/dashboard/dashboard_pending_debate.php");
    }

}