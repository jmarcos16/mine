<?php

namespace Jmarcos16\Mine;

readonly class RegisterRoutes
{
    protected array $routes;

    public function __construct(
        protected array $controllers
    ) {
    }

    public function register(): void
    {
        foreach ($this->controllers as $controller) {
            $reflection = new \ReflectionClass($controller);
            $methods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);

            foreach ($methods as $method) {
                $attributes = $method->getAttributes(Attribute\Route::class);

                foreach ($attributes as $attribute) {
                    $route = $attribute->newInstance();
                    $uri = $route->getUri();
                    $methods = $route->getMethods();

                    foreach ($methods as $method) {
                        $this->routes[$method][$uri] = [
                            'controller' => $controller,
                            'actions' => $method->getName(),
                        ];
                    }
                }
            }
        }
    }
}