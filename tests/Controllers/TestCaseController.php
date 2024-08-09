<?php

namespace Jmarcos16\RouterMine\Tests\Controllers;

use Jmarcos16\RouterMine\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class TestCaseController
{
    #[Route('/default-route')]
    public function index(): void
    {
        echo 'Hello from TestController in default route';
    }

    #[Route('/with-params/{id}')]
    public function show(int $id): void
    {
        echo "Hello from TestController with id $id";
    }

    #[Route('/multiple-params/{id}/{name}')]
    public function multipleParams(int $id, string $name): void
    {
        echo "Hello from TestController with id $id and name $name";
    }

    #[Route('/multiple-params-with-request/{id}/{name}')]
    public function multipleParamsWithRequest(int $id, string $name, Request $request): void
    {
        echo "Hello from TestController with id $id and name $name and method {$request->getMethod()}";
    }

    #[Route('/default-route', 'POST')]
    public function indexPost(): void
    {
        echo 'Hello from TestController in default route with method POST';
    }

    #[Route('/with-params/{id}', 'POST')]
    public function showPost(int $id): void
    {
        echo "Hello from TestController with id $id with method POST";
    }

    #[Route('/multiple-methods', ['GET', 'POST'])]
    public function multipleMethods(): void
    {
        echo 'Hello from TestController with multiple methods';
    }
}
