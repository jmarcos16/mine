<?php

use Jmarcos16\Mine\Exceptions\RouteNotFoundException;
use Jmarcos16\Mine\Exceptions\RouterException;
use Jmarcos16\Mine\Router;
use Jmarcos16\Mine\Tests\Controllers\TestController;
use Symfony\Component\HttpFoundation\Request;

it('cannot find a route', function () {
    $router = new Router([TestController::class]);
    $request = Request::create('/not-found', 'GET');

    $router->handle($request);
})->throws(RouterException::class, 'Route not found');

it('cannot call a controller that does not exist', function () {
    new Router(['Not\Found\Controller']);
})->throws(RouterException::class, 'Controller not found');

it('cannot call a controller route that does not exist', function () {
    $router = new Router([TestController::class]);
    $request = Request::create('/not-found', 'GET');

    $router->handle($request);
})->throws(RouterException::class, 'Route not found');

it('should call a controller route', function () {
    $router = new Router([TestController::class]);
    $request = Request::create('/test', 'GET');

    $router->handle($request);
})->expectOutputString('Hello from TestController');