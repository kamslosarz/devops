<?php

namespace Application\Router;

use Application\Config\Config;

class Router
{
    private static $routes;
    private $requestUri;
    private $parameters;

    public function __construct($requestUri = '')
    {
        $this->requestUri = $requestUri;
    }

    /**
     * @return Route
     * @throws RouteException
     */
    public function __invoke()
    {
        foreach(self::getRoutes() as $uriPattern => $route)
        {
            if($this->match($uriPattern, $this->requestUri))
            {
                return new Route($route, $this->parameters);
            }
        }

        throw new RouteException(sprintf('Route \'%s\' not found', $this->requestUri));
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

    private static function getRoutes()
    {
        if(empty(self::$routes))
        {
            foreach(Config::get('routes') as $routeName => $route)
            {
                $route[0] = preg_replace("/[a-z0-9]+\\\\Controller\\\\(.+)$/i", "$1", $route[0]);
                self::$routes[$routeName] = $route;
            }
        }

        return self::$routes;
    }

    public static function getCompactRouteName($controller, $action)
    {
        return sprintf('%s:%s', $controller, str_replace('Action', '', $action));
    }

    /**
     * @param $controller
     * @param $action
     * @param array $parameters
     * @return int|mixed|null|string
     * @throws RouteException
     */
    public static function getRouteUrlByParameters($controller, $action, $parameters = [])
    {
        $relativeUrl = null;

        foreach(self::getRoutes() as $uri => $route)
        {
            if(isset($route[2]))
            {
                unset($route[2]);
            }

            if([$controller, $action] === $route)
            {
                $relativeUrl = $uri;

                foreach($parameters as $key => $value)
                {
                    $relativeUrl = str_replace(sprintf('[%s]', $key), $value, $relativeUrl);
                }
            }
        }

        if(!$relativeUrl)
        {
            throw new RouteException(sprintf('Route pattern \'%s:%s\' with parameters \'%s\' not found', $controller, $action, implode(',', $parameters)));
        }

        return $relativeUrl;
    }
}