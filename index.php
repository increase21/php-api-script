<?php

require_once './helpers/helper.php';
require_once './router.php';
require_once './auth/auth.php';

$method = $_SERVER['REQUEST_METHOD'];
$url_path = substr($_SERVER['REQUEST_URI'], 5);
$input = json_decode(file_get_contents('php://input'));

$Auth = new auth(getallheaders(), true);
new router($url_path, $method, $input, $Auth->result);