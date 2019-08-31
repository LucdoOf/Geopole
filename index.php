<?php
require_once("conf.inc.php");
require_once("vendor/autoload.php");
require_once(APPLICATION_PATH."/controllers/Router.php");
$router = new Router();
$router->routeReq();