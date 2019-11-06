<?php

require_once './helpers/helper.php';
require_once './router.php';
require_once './auth/auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$url_path = substr($_SERVER['REQUEST_URI'], 5);
$input = json_decode(file_get_contents('php://input'));
// check auth
$Auth = new auth(getallheaders(), true);
$check_auth = $Auth::Check_Auth('Authorization', '/^Bearer\s[\w\.\-]{44}$/');
// $Auth::Check_IP(['::1']);
// route and execute the script
// $router = new router($url_path, $method, $input, $check_auth);
$router = new router($url_path, $method, $input);
$router->run();
