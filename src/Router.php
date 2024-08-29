<?php

namespace Jmarcos16\MiniRouter;

use Jmarcos16\MiniRouter\Exceptions\RouterException;

class Router extends RegisterRouter
{
    /**
     * @param array<string> $controllers
     */
    public function __construct(
        array $controllers
    ) {
        parent::__construct($controllers);
    }

    public function handle(?Request $request = null): mixed
    {

        $request = $request ?? Request::capture();
        $routes  = $this->getRoutes();

        if(!isset($routes[$request->getMethod()])) {
            throw new RouterException('Route not found', 404);
        }

        foreach ($routes[$request->getMethod()] as $key => $route) {
            if ($this->matchRoute($key, $request->uri(), $params)) {
                return ContainerFactory::make([
                    'controller' => $route['controller'],
                    'action'     => $route['actions'],
                ], $params);
            }
        }

        throw new RouterException('Route not found', 404);
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
