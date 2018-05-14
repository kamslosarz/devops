<?php

namespace Application\Router;

class Route
{
    const ACCESS_PUBLIC = 'public';
    const ACCESS_PRIVATE = 'private';

    private $controller;
    private $action;
    private $parameters;
    private $access;

    public function __construct($routeParameters, $controllerParameters)
    {
        $this->controller = $routeParameters[0];
        $this->action = $routeParameters[1];
        $this->access = (isset($routeParameters[2]) && $routeParameters[2] === self::ACCESS_PUBLIC)? self::ACCESS_PUBLIC : self::ACCESS_PRIVATE;
        $this->parameters = $controllerParameters;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function getAccess()
    {
        return $this->access;
    }
}