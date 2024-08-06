<?php

namespace Jmarcos16\Mine;

use DI\Container;
use Jmarcos16\Mine\Exceptions\RouterException;
use Symfony\Component\HttpFoundation\Request;

class Router
{
    private array $routes = [];
    private Container $container;

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

    public function handle(Request $request)
    {
        $uri    = $request->getPathInfo();
        $method = $request->getMethod();

        foreach ($this->routes as $key => $route) {
            if(in_array($method, $route['methods']) && $this->matchRoute($key, $uri, $params)) {
                $this->makeInstance($route['controller'], $route['actions'], $params);
                return;
            }
        }

        throw new RouterException('Route not found', 404);
    }

    private function makeInstance(string $controller, string $actions, array $params)
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
            }
        }

        $this->container->call([$controller, $actions], array_merge($parameters, $params));
    }

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

    private function addRoutesFromController($controller)
    {

        if(!class_exists($controller)){
            throw new RouterException('Controller not found', 404);
        }

        $reflection = new \ReflectionClass($controller);

        $actions = $reflection->getMethods();

        foreach ($actions as $actions) {
            $attributes = $actions->getAttributes();

            foreach ($attributes as $attribute) {
                if ($attribute->getName() === 'Jmarcos16\Mine\Attribute\Route') {
                    $route = $attribute->newInstance();
                    $this->addRoute($route->getUri(), $controller, $actions->getName(), $route->getMethods());
                }
            }
        }
    }

    private function addRoute(string $uri, $controller, string $actions, array $methods = ['GET']): void
    {
        $this->routes[$uri] = [
            'controller' => $controller,
            'actions'    => $actions,
            'methods'    => $methods,
        ];
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
