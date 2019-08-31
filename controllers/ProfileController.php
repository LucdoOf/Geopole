<?php
class ProfileController
{

    public function __construct($url, $params)
    {
        if(isset($url) && count($url) !== 2){
            throw new Exception("Page introuvable");
        } else {
            $userPseudo = $url[1];
            $user = User::getByPseudo($userPseudo);
            if($user->exist()){
                $this->profile($url, $user);
            } else {
                throw new Exception("Utilisateur introuvable (2)");
            }
        }
    }

    private function profile($url, $user){
        $comments = Comment::getUserComments($user);
        require_once APPLICATION_PATH."/views/profile.php";
    }

}