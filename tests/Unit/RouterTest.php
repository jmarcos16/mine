<?php

use Jmarcos16\Mine\Exceptions\RouteNotFoundException;
use Jmarcos16\Mine\Router\Router;
use Jmarcos16\Mine\Tests\Controllers\TestController;
use Symfony\Component\HttpFoundation\Request;

it('should add a route', function () {
    $router = new Router(
        controllers: [
            new TestController(),
        ]
    );

    $routes = $router->getRoutes();

    expect($routes)->toHaveCount(1);
    expect($routes['/test']['controller'])->toBeInstanceOf(TestController::class);
    expect($routes['/test']['actions'])->toBe('index');
});

it('should be able to get the methods of a route', function () {
    $router = new Router(
        controllers: [
            new TestController(),
        ]
    );

    $routes = $router->getRoutes();

    expect($routes['/test']['methods'])->toBe(['GET']);
});

it('should throw an exception when the route is not found', function () {
    $router = new Router(
        controllers: [
            new TestController(),
        ]
    );

    $request = new Symfony\Component\HttpFoundation\Request();
    $request->server->set('REQUEST_URI', '/not-found');
    $request->setMethod('GET');

    $this->expectException(RouteNotFoundException::class);
    $router->handle($request);
});

it('should be able resolve params {param} in the route', function () {
    $router = new Router(
        controllers: [
            new TestController(),
        ]
    );

    $request = new Request();
    $request->server->set('REQUEST_URI', '/test/1');
    $request->setMethod('GET');

    $controller = $router->handle($request);

    // dd($controller->getParams());

    // expect($controller->getParams())->toBe(['id' => 1]);
});
