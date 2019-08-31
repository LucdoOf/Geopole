<?php
class Router
{
    private $_ctrl;
    private $_view;

    public function __construct(){
        spl_autoload_register(function($class){
            if(file_exists(APPLICATION_PATH."/models/objects/".$class.".php")){
                require_once(APPLICATION_PATH."/models/objects/".$class.".php");
            } else if(file_exists(APPLICATION_PATH."/inc/".$class.".php")){
                require_once(APPLICATION_PATH."/inc/".$class.".php");
            } else if(file_exists(APPLICATION_PATH."/controllers/".$class.".php")){
                require_once(APPLICATION_PATH."/controllers/".$class.".php");
            } else if(file_exists(APPLICATION_PATH."/inc/socials/".$class.".php")){
                require_once(APPLICATION_PATH."/inc/socials/".$class.".php");
            } else if(file_exists(APPLICATION_PATH."/models/".$class.".php")){
                require_once(APPLICATION_PATH."/models/".$class.".php");
            }
        });
    }

    public function routeReq(){
        try {
            $url = [];

            if(isset($_GET["action"])){

                /**$paramString = explode("/", $_GET["action"])[count(explode("/", $_GET["action"]))-1];
                $params = [];
                foreach (explode("-", $paramString) as $param){
                    if(strpos($param, "=") != 0){
                        $params[explode("=", $param)[0]] = explode("=", $param)[1];
                    }
                }**/

                $paramString = explode("&",explode("?",$_SERVER["REQUEST_URI"])[1]);
                $params = [];
                foreach ($paramString as $param){
                    if(!is_null(explode("=", $param)[1])) {
                        $params[explode("=", $param)[0]] = explode("=", $param)[1];
                    }
                }

                $url = explode("/", filter_var($_GET["action"], FILTER_SANITIZE_URL));

                $controller = ucfirst(strtolower($url[0]));

                if(strpos($controller, "_") !== false){
                    $controllersExplode = explode("_", $controller);
                    $controller = "";
                    foreach ($controllersExplode as $controllerExplode){
                        $controller = $controller . ucfirst(strtolower($controllerExplode));
                    }
                }

                $controllerClass = $controller."Controller";
                $controllerFile = "controllers/".$controllerClass.".php";

                if(file_exists($controllerFile)){
                    require_once($controllerFile);
                    $this->_ctrl = new $controllerClass($url, $params);
                } else {
                    http_response_code(404);
                    throw new Exception('Page introuvable', 404);
                }
            } else {
                require_once('controllers/HomeController.php');
                $this->_ctrl = new HomeController($url, []);
            }
        } catch (Exception $e){
            $errorMsg = $e->getMessage();
            http_response_code($e->getCode());
            require_once("views/error.php");
        }
    }
}
