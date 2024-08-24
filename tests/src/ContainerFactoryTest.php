<?php

namespace Jmarcos16\MiniRouter\Tests;

use Jmarcos16\MiniRouter\ContainerFactory;
use Jmarcos16\MiniRouter\Exceptions\RouterException;
use PHPUnit\Framework\TestCase;

class ContainerFactoryTest extends TestCase
{
    public function testCallWithValidCallable()
    {
        $callable = [
            'controller' => TestController::class,
            'action'     => 'testAction',
        ];
        $params = ['param1', 'param2'];

        $result = ContainerFactory::make($callable, $params);

        $this->assertEquals('Action called with param1 and param2', $result);
    }

    public function testCallWithInvalidCallable()
    {
        $this->expectException(RouterException::class);
        $this->expectExceptionMessage('Action not found');

        $callable = [
            'controller' => TestController::class,
            'action'     => 'nonExistentAction',
        ];
        $params = [];

        ContainerFactory::make($callable, $params);
    }
}

class TestController
{
    public function testAction($param1, $param2)
    {
        return "Action called with $param1 and $param2";
    }
}
