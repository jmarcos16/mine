<?php
namespace Jmarcos16\Mine\Tests\Controllers;
use Jmarcos16\Mine\Attribute\Route;
class Test1Controller
{
    #[Route('/test')]
    public function index()
    {
        echo 'Hello from Test1Controller';
    }
}