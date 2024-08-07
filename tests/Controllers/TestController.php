<?php
namespace Jmarcos16\Mine\Tests\Controllers;
use Jmarcos16\Mine\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class TestController
{
    #[Route('/test')]
    public function index()
    {
        echo 'Hello from TestController';
    }

    #[Route('/test/{id}')]
    public function show($id)
    {
        echo "Hello from TestController with id $id";
    }
}