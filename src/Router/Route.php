<?php

namespace Application\Router;

final class Route
{
    private $controller;
    private $action;
    private $parameters;

    public function __construct($routeParameters, $controllerParameters)
    {
        $this->controller = $routeParameters[0];
        $this->action = $routeParameters[1];
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
}