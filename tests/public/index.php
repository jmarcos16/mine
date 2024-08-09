<?php

use Jmarcos16\RouterMine\Router;
use Jmarcos16\RouterMine\Tests\Controllers\{TestCaseController};
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../../vendor/autoload.php';

$routes = [
    TestCaseController::class,
];

$router = new Router($routes);

$router->handle(Request::createFromGlobals());
