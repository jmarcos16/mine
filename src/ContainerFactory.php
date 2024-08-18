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
        $callable = $this->resolve($callable, $params);


        return call_user_func_array($callable, $params);
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

        if (!method_exists($callable[0], $callable[1])) {
            throw new RouterException('Action not found', 404);
        }

        $reflection = new \ReflectionMethod($callable[0], $callable[1]);
        $parameters = [];

        foreach ($reflection->getParameters() as $index => $parameter) {
            $dependency = $parameter->getType();

            if ($dependency instanceof \ReflectionNamedType && !$dependency->isBuiltin()) {
                $parameters[] = $parameter->getClass()->getName();
            } else {
                // TODO: add the params in the correct request
                $parameters[] = array_shift($params);
            }
        }

        return $parameters;
    }


}