<?php
require 'api/config/DatabaseConnector.php';
require 'api/controller/UserController.php';

use Api\Config\DatabaseConnector;
use Api\Controller\UserController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_array = explode( '/', $uri );

// all of our endpoints start with /user
// everything else results in a 404 Not Found
if ($uri_array[1] !== 'user') {
    header("HTTP/1.1 404 Not Found");
    exit();
}


$dbConnection = (new DatabaseConnector())->getConnection();

$requestMethod = $_SERVER["REQUEST_METHOD"];

// pass the request method to the UserController and process the HTTP request:
$controller = new UserController($dbConnection, $requestMethod);
$controller->processRequest();
?>