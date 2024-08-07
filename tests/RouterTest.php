<?php

use Jmarcos16\Mine\Exceptions\RouterException;
use Jmarcos16\Mine\Router;
use Jmarcos16\Mine\Tests\Controllers\TestController;
use Symfony\Component\HttpFoundation\Request;

test('verify that a route can be added correctly', function () {
    $router = new Router([TestController::class]);
    $routes = $router->getRoutes();
    expect($routes)->toBeArray();
    expect($routes)->toHaveKey('GET');
    expect($routes['GET'])->toHaveKey('/test');
    expect($routes['GET']['/test'])->toBeArray();
    expect($routes['GET']['/test'])->toHaveKey('controller');
    expect($routes['GET']['/test']['controller'])->toBe(TestController::class);
});

test('verify the behavior when a non-existent route is requested', function () {
    $router = new Router([TestController::class]);
    $request = Request::create('/nonexistent');
    $router->handle($request);
})->throws(RouterException::class, 'Route not found');

test('verify the behavior when an HTTP method not allowed is used on a route', function () {
    $router = new Router([TestController::class]);
    $request = Request::create('/test', 'PUT');
    $router->handle($request);
})->throws(RouterException::class, 'Route not found');

test('verify if the parameters are being passed correctly to the controller', function () {
    $router = new Router([TestController::class]);
    $request = Request::create('/test/1', 'POST');
    $router->handle($request);
    expect(ob_get_clean())->toBe('Hello from TestController with id 1');
})->only();