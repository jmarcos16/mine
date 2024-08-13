<?php

use Jmarcos16\MiniRouter\Router;
use Jmarcos16\MiniRouter\Tests\Controllers\{TestCaseController};
use Symfony\Component\HttpFoundation\Request;

require __DIR__ . '/../../vendor/autoload.php';

$routes = [
    TestCaseController::class,
];

$router = new Router($routes);

$router->handle(Request::createFromGlobals());
