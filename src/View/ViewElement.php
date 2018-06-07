<?php

namespace Application\View;

use Application\Router\Route;

class ViewElement implements ViewElementInterface
{
    protected $viewName;
    protected $parameters;

    public function __construct($route, $parameters = [])
    {
        if($route instanceof Route)
        {
            preg_match("/[a-z]+\\\+[a-z]+/", str_replace('controller', '', strtolower($route->getController())), $match);
            $namespace = ltrim(str_replace('-action', '', strtolower(preg_replace("/([A-Z])/x", "-$1", $route->getAction()))), '-');

            $this->viewName = str_replace('\\', DIRECTORY_SEPARATOR, $match[0]) . DIRECTORY_SEPARATOR . $namespace;
        }
        else
        {
            $this->viewName = $route;
        }

        $this->parameters = $parameters;
    }

    public function getViewName()
    {
        return $this->viewName;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}
