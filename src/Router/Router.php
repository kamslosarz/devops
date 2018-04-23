<?php

namespace Application\Router;

use Application\Config\Config;

final class Router
{
    private $requestUri;
    private $routes;
    private $parameters;

    public function __construct($requestUri = '')
    {
        $this->requestUri = $requestUri;

        $this->loadRoutes();
    }

    /**
     * @return mixed
     * @throws RouteException
     */
    public function __invoke()
    {
        foreach($this->routes as $uriPattern => $route)
        {
            if($this->match($uriPattern, $this->requestUri))
            {
                return new Route($route, $this->parameters);
            }
        }

        throw new RouteException(sprintf('Route "%s" not found', $this->requestUri));
    }

    private function match($route, $uri)
    {
        $uri = explode('/', $uri);
        $route = explode('/', $route);
        $this->parameters = [];

        foreach($uri as $key => $value)
        {
            if($route[$key] !== $value)
            {
                if(preg_match('/^\[([a-zA-Z0-9]+)\]$/', $route[$key], $match))
                {
                    $this->parameters[$match[1]] = $value;
                }
                else
                {
                    return false;
                }
            }
        }

        return true;
    }

    private function loadRoutes()
    {
        foreach(Config::get('routes') as $route => $dest)
        {
            $this->routes[$route] = $dest;
        }
    }

    public function getRouteByParameters($controller, $action, $parameters)
    {
        $relativeUrl = '';
        foreach($this->routes as $uri => $route)
        {
            if([$controller, $action] === $route)
            {
                $relativeUrl = $uri;

                foreach($parameters as $key => $value)
                {
                    $relativeUrl = str_replace(sprintf('[%s]', $key), $value, $relativeUrl);
                }
            }
        }

        return $relativeUrl;
    }
}