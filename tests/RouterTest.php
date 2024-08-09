<?php

namespace Jmarcos16\RouterMine\Tests;

use Jmarcos16\RouterMine\Router;
use Jmarcos16\RouterMine\Tests\Controllers\TestCaseController;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends FrameworkTestCase
{
    public function testItShouldReturnHelloFromTestControllerInDefaultRoute(): void
    {
        $request = Request::create('/default-route', 'GET');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController in default route');
    }

    public function testItShouldReturnHelloFromTestControllerWithId1(): void
    {
        $request = Request::create('/with-params/1', 'GET');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with id 1');
    }

    public function testItShouldReturnRouteNotFound(): void
    {
        $request = Request::create('/not-found', 'GET');
        $router  = new Router([TestCaseController::class]);
        $this->expectExceptionMessage('Route not found');
        $this->expectExceptionCode(404);
        $router->handle($request);
    }

    public function testItShouldReturnHelloFromTestControllerWithId1AndNameMarcos(): void
    {
        $request = Request::create('/multiple-params/1/marcos', 'GET');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with id 1 and name marcos');
    }

    public function testItShouldReturnHelloFromTestControllerWithId1AndNameMarcosUsingRequest(): void
    {
        $request = Request::create('/multiple-params-with-request/1/marcos', 'GET');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with id 1 and name marcos and method GET');
    }

    public function testItShouldReturnHelloFromTestControllerInDefaultRouteWithMethodPost(): void
    {
        $request = Request::create('/default-route', 'POST');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController in default route with method POST');
    }

    public function testItShouldReturnHelloFromTestControllerWithId1WithMethodPost(): void
    {
        $request = Request::create('/with-params/1', 'POST');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with id 1 with method POST');
    }

    public function testItShouldReturnHelloFromTestControllerWithMultipleMethods(): void
    {
        $request = Request::create('/multiple-methods', 'GET');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with multiple methods');
    }

    public function testItShouldReturnHelloFromTestControllerWithMultipleMethodsUsingPost(): void
    {
        $request = Request::create('/multiple-methods', 'POST');
        $router  = new Router([TestCaseController::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with multiple methods');
    }

    public function testItShouldReturnRouteNotFoundWithMethodPost(): void
    {
        $request = Request::create('/not-found', 'POST');
        $router  = new Router([TestCaseController::class]);
        $this->expectExceptionMessage('Route not found');
        $this->expectExceptionCode(404);
        $router->handle($request);
    }
}
