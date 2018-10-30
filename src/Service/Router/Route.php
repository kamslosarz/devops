<?php

namespace Application\Service\Router;

use Application\EventManager\EventListenerInterface;
use Application\Service\Request\RequestMethods;

class Route
{
    private $controller;
    private $action;

    public function __construct($name, $route)
    {
        $this->name = $name;
        $this->controller = $route[0];
        $this->action = $route[1];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getController(): string
    {
        return $this->controller;
    }

    public function getAction(): string
    {
        return $this->action;
    }
}