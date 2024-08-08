<?php
namespace Jmarcos16\RouterMine\Tests\Controllers;

use Jmarcos16\RouterMine\Attribute\Route;

class TestController
{
    #[Route('/default-route')]
    public function index(): void
    {
        echo 'Hello from TestController in default route';
    }

    #[Route('/with-params/{id}')]
    public function show($id): void
    {
        echo "Hello from TestController with id $id";
    }
}