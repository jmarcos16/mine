<?php

namespace Jmarcos16\MiniRouter;

use DI\Container;
use Jmarcos16\MiniRouter\Exceptions\RouterException;
use Symfony\Component\HttpFoundation\Request;

class Router extends RegisterRouter
{
    private Container $container;

    /**
     * @param array<string> $controllers
     */
    public function __construct(
        array $controllers
    ) {

        parent::__construct($controllers);

        $this->container = new Container();
    }

    public function handle(Request $request): void
    {
        $uri    = $request->getPathInfo();
        $method = $request->getMethod();

        $routes = $this->getRoutes();

        if(!isset($routes[$method])) {
            throw new RouterException('Route not found', 404);
        }

        foreach ($routes[$method] as $key => $route) {
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
     *
     * @return bool
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
}
