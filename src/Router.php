<?php

namespace Jmarcos16\RouterMine;

use DI\Container;
use Jmarcos16\RouterMine\Attribute\Route;
use Jmarcos16\RouterMine\Exceptions\RouterException;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    /** @var array<string, array<string, array<string, string>>> $routes */
    private array $routes = [];

    private Container $container;

    /**
     * @param array<string> $controllers
     */
    public function __construct(
        array $controllers
    ) {
        if (!empty($controllers)) {
            foreach ($controllers as $controller) {
                $this->addRoutesFromController($controller);
            }
        }

        $this->container = new Container();
    }

    public function handle(Request $request): void
    {
        $uri    = $request->getPathInfo();
        $method = $request->getMethod();

        if(!isset($this->routes[$method])) {
            throw new RouterException('Route not found', 404);
        }

        foreach ($this->routes[$method] as $key => $route) {
            if ($this->matchRoute($key, $uri, $params)) {
                $this->makeInstance($route['controller'], $route['actions'], $params);

                return;
            }
        }

        throw new RouterException('Route not found', 404);
    }

    /**
     * @param string $controller
     * @param string $actions
     * @param array<mixed> $params
     */
    private function makeInstance(string $controller, string $actions, array $params): void
    {
        if (!method_exists($controller, $actions)) {
            throw new RouterException('Action not found', 404);
        }

        $reflection = new \ReflectionMethod($controller, $actions);
        $parameters = [];

        foreach ($reflection->getParameters() as $index => $parameter) {
            $dependency = $parameter->getType();

            if ($dependency instanceof \ReflectionNamedType && !$dependency->isBuiltin()) {
                $parameters[] = $this->container->get($dependency->getName());
            } else {
                // TODO: add the params in the correct request
                $parameters[] = array_shift($params);
            }
        }

        $this->container->call([$controller, $actions], array_merge($parameters));
    }

    /**
     * @param string $path
     * @param string $uri
     * @param array<mixed> $params
     */
    private function matchRoute(string $path, string $uri, &$params): bool
    {
        $path  = preg_replace('/{(\w+)}/', '(\w+)', $path);
        $regex = "#^$path$#";

        if (!preg_match($regex, $uri, $matches)) {
            return false;
        }

        $params = array_slice($matches, 1);

        return true;
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
