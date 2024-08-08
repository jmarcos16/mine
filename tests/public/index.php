<?php

use Jmarcos16\Mine\Tests\Controllers\TestController;
use Jmarcos16\RouterMine\Router;
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../../vendor/autoload.php';

$routes = [
    TestController::class,
];

$router = new Router($routes);

$router->handle(Request::createFromGlobals());

