<?php

namespace Jmarcos16\Mine\Router;

class Router
{
    protected $routes = [];

    // Controllers path
    protected $path;

    public function getRoutes()
    {
        return $this->routes;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

}