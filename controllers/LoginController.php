<?php
class LoginController
{

    private $_url;

    public function __construct($url, $params)
    {
        $this->_url = $url;
        if(isset($url) && count($url) > 2){
            throw new Exception("Page introuvable");
        } else {
            if(isset($url[1]) && $url[1] == "sign_up"){
                if(isset($_POST["email"]) && isset($_POST["first_name"]) && isset($_POST["last_name"]) && isset($_POST["pseudo"]) && isset($_POST["password"]) && isset($_POST["password_confirm"])) {
                    if(User::checkParameters($_POST["first_name"], $_POST["last_name"], $_POST["pseudo"], $_POST["password"], $_POST["email"])){
                        if($_POST["password"] === $_POST["password_confirm"]){
                            $userPseudo = User::getByPseudo($_POST["pseudo"]);
                            if(!$userPseudo->exist()){
                                $user = new User();
                                $user->setFirstName($_POST["first_name"]);
                                $user->setLastName($_POST["last_name"]);
                                $user->setPseudo($_POST["pseudo"]);
                                $user->setEmail($_POST["email"]);
                                $user->setPassword(password_hash($_POST["password"],PASSWORD_DEFAULT));
                                $user->save();
                                if($user->getId() > 0) {
                                    header('Location: /login');
                                } else {
                                    $this->signUp("Une erreur est survenue");
                                }
                            } else {
                                $this->signUp("Ce nom est déjà pris !");
                            }
                        } else {
                            $this->signUp("Les deux mots de passe de correspondent pas");
                        }
                    } else {
                        if(!User::checkPassword($_POST["password"])){
                            $this->signUp("Votre mot de passe doit contenir une majuscule, une minuscule et un caractère spécial. Il doit faire plus de 8 caractères");
                        } else {
                            if(!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)){
                                $this->signUp("Veuillez rentrer un email valide");
                            } else {
                                $this->signUp("Veuillez rentrer un nom un prénom et un pseudo corrects");
                            }
                        }
                    }
                } else {
                    $this->signUp();
                }
            } else {
                if(isset($_POST["pseudo"]) && isset($_POST["password"])){
                    $user = User::getByPseudo($_POST["pseudo"]);
                    if(password_verify($_POST["password"], $user->getPassword())){
                        $user->connect();
                        header('Location: /home');
                    } else {
                        $this->login("Identifiants incorrects");
                    }
                } else {
                    $this->login();
                }
            }
        }

    }

    private function login($error = NULL){
        if(!User::isConnected()){
            require_once(APPLICATION_PATH."/views/login.php");
        } else {
            header('Location: /dashboard');
        }
    }

    private function signUp($error = NULL){
        $signUp = true;
        if(!User::isConnected()){
            require_once(APPLICATION_PATH."/views/login.php");
        } else {
            header('Location: /dashboard');
        }
    }


}