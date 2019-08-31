<?php
class DashboardController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) > 2){
            throw new Exception("Page introuvable");
        } else {
            if(User::isConnected()) {
                if(count($url) == 1){
                    header("Location: /dashboard/presentation");
                } else if(count($url) == 2){
                    switch ($url[1]){
                        case "presentation":
                            if (isset($_POST["content"])) {
                                $user = User::getConnectedUser();
                                $user->setDescription($_POST["content"]);
                                $user->save();
                            }
                            $this->dashboardPresentation($url, isset($params["edit"]));
                            break;
                        case "search_member":
                            $this->dashboardSearchMember($url);
                            break;
                        case "notifications":
                            if(isset($params["send"]) && isset($_POST["content"])){
                                if(User::getConnectedUser()->getRank() > 1){
                                    Notification::sendGlobalNotification("Notification générale", $_POST["content"], "/home", NULL);
                                    //header("Location: /dashboard/notifications");
                                } else {
                                    $this->dashboardNotifications($url);
                                }
                            } else {
                                $this->dashboardNotifications($url);
                            }
                            break;
                        case "follows":
                            $this->dashboardFollows($url);
                            break;
                        case "messages":
                            if(isset($params["foreigner"])){
                                $foreigner = (int) $params["foreigner"];
                                if($foreigner >= 0){
                                    $this->dashboardMessages($url, $foreigner);
                                }
                            } else {
                                $this->dashboardMessages($url, isset($foreigner));
                            }
                            break;
                        case "profile":
                            if(isset($_POST["image"]) && !empty($_POST["image"])){
                                $user = User::getConnectedUser();
                                $user->setProfile_pic($_POST["image"]);
                                $user->save();
                            } else {
                                $this->dashboardProfile($url);
                            }
                            break;
                        default:
                            throw new Exception("Page introuvable (2)");
                            break;
                    }
                }
            } else {
                header("Location: /login");
            }
        }

    }

    private function dashboardPresentation($url, $edit){
        require_once(APPLICATION_PATH."/views/dashboard/dashboard_presentation.php");
    }

    private function dashboardSearchMember($url){
        require_once(APPLICATION_PATH."/views/dashboard/dashboard_search_member.php");
    }

    private function dashboardNotifications($url){
        require_once(APPLICATION_PATH."/views/dashboard/dashboard_notifications.php");
    }

    private function dashboardFollows($url){
        require_once(APPLICATION_PATH . "/views/dashboard/dashboard_follows.php");
    }

    private function dashboardMessages($url, $foreigner){
        require_once(APPLICATION_PATH . "/views/dashboard/dashboard_messages.php");
    }

    private function dashboardProfile($url){
        require_once(APPLICATION_PATH . "/views/dashboard/dashboard_profile.php");
    }

}