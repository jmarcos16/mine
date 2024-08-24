<?php

namespace Jmarcos16\MiniRouter;

use Jmarcos16\MiniRouter\Exceptions\RouterException;

class ContainerFactory
{
    /**
     * Instance of Controller and resolve dependencies
     *
     * @param array<string> $callable
     * @param array <mixed> $params
     *
     * @return mixed
     */
    public function call(array $callable, array $params = []): mixed
    {
        $parameters = $this->resolve($callable, $params);

        return call_user_func_array([new $callable['controller'](), $callable['action']], $parameters);
    }

    /**
     * Resolve dependencies
     *
     * @param array<string> $callable
     * @param array <mixed> $params
     *
     * @return array<string>
     */
    private function resolve(array $callable, array $params = []): array
    {
        if (!method_exists($callable['controller'], $callable['action'])) {
            throw new RouterException('Action not found', 404);
        }

        $reflection = new \ReflectionMethod($callable['controller'], $callable['action']);
        $parameters = [];

        foreach ($reflection->getParameters() as $index => $parameter) {
            /** @var \ReflectionNamedType|null $dependency */
            $dependency = $parameter->getType();
            
            if ($dependency) {
                $dependency   = $dependency->getName();
                $parameters[] = new $dependency();
            } else {
                $parameters[] = $params[$index] ?? null;
            }
        }

        return $parameters;
    }

    /**
     * Instance class and call method
     *
     * @param array<string> $callable
     * @param array <mixed> $params
     *
     * @return mixed
     */
    public static function make(array $callable, array $params = []): mixed
    {
        return (new self())->call($callable, $params);
    }
}
