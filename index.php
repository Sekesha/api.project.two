<?php



// show error messages
ini_set('display_errors', 1);
error_reporting(E_ALL);

// folder path
define('ROOT', dirname(__FILE__));

require_once ROOT.'/components/Db.php';
require_once ROOT.'/components/ApiRouter.php';
require_once ROOT.'/controllers/UserController.php';
require_once ROOT . '/components/HeaderSender.php';


if (UserController::checkAuth()){
    $router = new ApiRouter();
    $router->run();
}else{
    HeaderSender::Send(401, "Not authorized");
}








