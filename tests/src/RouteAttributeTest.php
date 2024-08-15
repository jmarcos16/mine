<?php

namespace Tests\src;

use Jmarcos16\MiniRouter\Attribute\Route;
use PHPUnit\Framework\TestCase;

class RouteAttributeTest extends TestCase
{
    public function testItShouldReturnRouteAttribute(): void
    {
        $class = new class {
            #[Route('/home')]
            public function index(): string
            {
                return 'Hello World';
            }
        };


        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('index');

        $attributes = $method->getAttributes(Route::class);
        $this->assertCount(1, $attributes);

        $attributeInstance = $attributes[0]->newInstance();
        $this->assertEquals('/home', $attributeInstance->getUri());
    }

    public function testItShouldReturnRouteAttributeWithMethods(): void
    {
        $class = new class {
            #[Route('/home', ['GET', 'POST'])]
            public function index(): string 
            {
                return 'Hello World';
            }
        };

        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('index');

        $attributes = $method->getAttributes(Route::class);
        $this->assertCount(1, $attributes);

        $attributeInstance = $attributes[0]->newInstance();
        $this->assertEquals(['GET', 'POST'], $attributeInstance->getMethods());
    }

    public function testItShouldReturnRouteAttributeWithName(): void
    {
        $class = new class {
            #[Route('/home', name: 'home')]
            public function index(): string
            {
                return 'Hello World';
            }
        };

        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('index');

        $attributes = $method->getAttributes(Route::class);
        $this->assertCount(1, $attributes);

        $attributeInstance = $attributes[0]->newInstance();
        $this->assertEquals('home', $attributeInstance->getName());
    }

    public function testItShouldReturnRouteAttributeWithAllParameters(): void
    {
        $class = new class {
            #[Route('/home', ['GET', 'POST'], name: 'home')]
            public function index(): string
            {
                return 'Hello World';
            }
        };

        $reflection = new \ReflectionClass($class);
        $method = $reflection->getMethod('index');

        $attributes = $method->getAttributes(Route::class);
        $this->assertCount(1, $attributes);

        $attributeInstance = $attributes[0]->newInstance();
        $this->assertEquals('/home', $attributeInstance->getUri());
        $this->assertEquals(['GET', 'POST'], $attributeInstance->getMethods());
        $this->assertEquals('home', $attributeInstance->getName());
    }

}

