<?php
require 'api/controller/UserController.php';

use Api\Controller\UserController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_array = explode( '/', $uri );

// all of our endpoints start with /cytech/user
// check if the user is in the URI
if ($uri_array[2] !== 'user') {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// In order to get the user with userid and email, the URI
// will be like /cytech/user/userid/$userid and /cytech/user/email/$email
// so we need to check if the values of $userid and $email are there.
$userId = null;
$email = null;
if (!empty($uri_array[4])) {
    if ($uri_array[3] == "userid") {
        $userId = (int) $uri_array[4];
    } else if ($uri_array[3] == "email") {
        $email = $uri_array[4];
    } else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
} else if (empty($uri_array[4]) && !empty($uri_array[3])) {
    header("HTTP/1.1 404 Not Found");
    exit();
}

// pass the request method to the UserController and process the HTTP request:
$controller = new UserController($userId, $email);
$controller->processRequest();
?>