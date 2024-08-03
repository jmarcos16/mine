<?php

use Jmarcos16\Mine\Router;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../../vendor/autoload.php';

if(!file_exists(base_path('config/config.php'))){
    throw new Exception('Config file not found');
}

$config = require base_path('config/config.php');

if(!isset($config['controllers'])){
    throw new Exception('Controllers not found');
}

$router = new Router($config['controllers']);
$router->handle(Request::createFromGlobals());

