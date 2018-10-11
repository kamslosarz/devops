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
    private $urlPattern;
    private $method;
    private $name;

    public function __construct($name, $routeParameters, $controllerParameters)
    {
        $this->controller = $routeParameters['controller'];
        $this->action = $routeParameters['action'];
        $this->access = (isset($routeParameters['access']) && $routeParameters['access'] === self::ACCESS_PUBLIC) ? self::ACCESS_PUBLIC : self::ACCESS_PRIVATE;
        $this->method = isset($routeParameters['method']) ? $routeParameters['method'] : null;
        $this->urlPattern = $routeParameters['url'];
        $this->name = $name;
        $this->parameters = $controllerParameters;
    }

    public function getUrl()
    {
        $url = $this->urlPattern;

        foreach($this->parameters as $key => $value)
        {
            $url = str_replace(sprintf('[%s]', $key), $value, $url);
        }

        return $url;
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

    public function getUrlPattern()
    {
        return $this->urlPattern;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getName()
    {
        return $this->name;
    }
}