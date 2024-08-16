<?php

namespace Jmarcos16\MiniRouter\Tests;

use Jmarcos16\MiniRouter\Attribute\Route;
use Jmarcos16\MiniRouter\Request as MiniRouterRequest;
use Jmarcos16\MiniRouter\Router;
use Jmarcos16\MiniRouter\Tests\Controllers\TestCaseController;
use PHPUnit\Framework\TestCase as FrameworkTestCase;
use Symfony\Component\HttpFoundation\Request;

class RouterTest extends FrameworkTestCase
{
    public function testItShouldReturnHelloFromTestControllerInDefaultRoute(): void
    {

        $controller = new class () {
            #[Route(uri: '/default-route', methods: ['POST'])]
            public function defaultRoute(): void
            {
                echo 'Hello from TestController in default route';
            }
        };
        
        Request::create('/default-route', 'GET');


        $router = new Router([$controller::class]);
        $router->handle();
        $this->expectOutputString('Hello from TestController in default route');
    }

    public function testItShouldReturnHelloFromTestControllerWithId1(): void
    {

        $controller = new class () {
            #[Route(uri: '/with-params/{id}', methods: ['GET'])]
            public function withParams(int $id): void
            {
                echo "Hello from TestController with id $id";
            }
        };
        $request = Request::create('/with-params/1', 'GET');

        $router = new Router([$controller::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with id 1');
    }

    public function testItShouldReturnRouteNotFound(): void
    {

        $controller = new class () {
            #[Route(uri: '/valid-route', methods: ['GET'])]
            public function withParams(int $id): void
            {
                echo "Hello from TestController with id $id";
            }
        };

        $request = Request::create('/not-found', 'GET');
        $router  = new Router([$controller::class]);
        $this->expectExceptionMessage('Route not found');
        $this->expectExceptionCode(404);
        $router->handle($request);
    }

    public function testItShouldReturnHelloFromTestControllerWithId1AndNameMarcos(): void
    {

        $controller = new class () {
            #[Route(uri: '/multiple-params/{id}/{name}', methods: ['GET'])]
            public function multipleParams(int $id, string $name): void
            {
                echo "Hello from TestController with id $id and name $name";
            }
        };

        $request = Request::create('/multiple-params/1/marcos', 'GET');
        $router  = new Router([$controller::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with id 1 and name marcos');
    }

    public function testItShouldReturnHelloFromTestControllerInDefaultRouteWithMethodPost(): void
    {

        $controller = new class () {
            #[Route(uri: '/default-route', methods: ['POST'])]
            public function defaultRoute(): void
            {
                echo 'Hello from TestController in default route with method POST';
            }
        };
        $request = Request::create('/default-route', 'POST');

        $router = new Router([$controller::class]);
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
        $controller = new class () {
            #[Route(uri: '/multiple-methods', methods: ['GET', 'POST'])]
            public function multipleMethods(): void
            {
                echo 'Hello from TestController with multiple methods';
            }
        };

        $request = Request::create('/multiple-methods', 'GET');
        $router  = new Router([$controller::class]);
        $router->handle($request);
        $this->expectOutputString('Hello from TestController with multiple methods');
    }
}
