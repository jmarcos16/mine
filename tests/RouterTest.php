<?php

namespace Jmarcos16\MiniRouter\Tests;

use Jmarcos16\MiniRouter\Attribute\Route;
use Jmarcos16\MiniRouter\{Request as MiniRouterRequest, Router};
use PHPUnit\Framework\TestCase as FrameworkTestCase;

class RouterTest extends FrameworkTestCase
{
    public function testItShouldHandleRoute(): void
    {
        $controller = new class () {
            #[Route(uri: '/default-route', methods: ['POST'])]
            public function defaultRoute(): void
            {
                echo 'Hello from TestController in default route';
            }
        };

        $router = new Router([$controller::class]);
        $router->handle(MiniRouterRequest::create('/default-route', 'POST'));
        $this->expectOutputString('Hello from TestController in default route');
    }

    public function testItShouldThrowExceptionWhenRouteNotFound(): void
    {
        $controller = new class () {
            #[Route(uri: '/default-route', methods: ['POST'])]
            public function defaultRoute(): void
            {
                echo 'Hello from TestController in default route';
            }
        };

        $router = new Router([$controller::class]);
        $this->expectExceptionMessage('Route not found');
        $router->handle(MiniRouterRequest::create('/not-found-route', 'POST'));
    }

    public function testItShouldThrowExceptionWhenRouteNotFoundAndMethodIsNotDefined(): void
    {
        $controller = new class () {
            #[Route(uri: '/default-route', methods: ['POST'])]
            public function defaultRoute(): void
            {
                echo 'Hello from TetsController in default route';
            }
        };
        $router = new Router([$controller::class]);
        $this->expectExceptionMessage('Route not found');
        $router->handle(MiniRouterRequest::create('/default-route', 'GET'));
    }
}
