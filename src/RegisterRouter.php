<?php

namespace Jmarcos16\MiniRouter;

use Jmarcos16\MiniRouter\Attribute\Route;
use Jmarcos16\MiniRouter\Exceptions\RouterException;

class RegisterRouter
{

    private array $routes = [];

    public function __construct(
        protected array $controllers = []
    ) {
        $this->registerRoutes();
    }


    private function registerRoutes(): void
    {
        if (!empty($controllers)) {
            foreach ($controllers as $controller) {
                $this->addRoutesFromController($controller);
            }
        }
    }

    /**
     * @param string $controller
     */
    private function addRoutesFromController($controller): void
    {

        if(!class_exists($controller)) {
            throw new RouterException('Controller not found', 404);
        }

        $reflection = new \ReflectionClass($controller);

        $actions = $reflection->getMethods();

        foreach ($actions as $actions) {
            $attributes = $actions->getAttributes();

            foreach ($attributes as $attribute) {
                if ($attribute->getName() === Route::class) {
                    $route = $attribute->newInstance();
                    $this->addRoute($route->getUri(), $controller, $actions->getName(), $route->getMethods());
                }
            }
        }
    }

    /**
     * @param string $uri
     * @param string $controller
     * @param string $actions
     * @param array<string> $methods
     */
    private function addRoute(string $uri, string $controller, string $actions, array $methods = ['GET']): void
    {
        foreach ($methods as $method) {
            $this->routes[$method][$uri] = [
                'controller' => $controller,
                'actions'    => $actions,
            ];
        }
    }

    /**
     * Get the value of routes
     *
     * @return array<string, array<string, array<string, string>>> $routes
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    
}