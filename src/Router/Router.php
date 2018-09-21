<?php

namespace Application\Router;

use Application\Config\Config;

class Router
{
    private static $routes;
    private $requestUri;
    private $parameters;
    private $requestMethod;

    public function __construct($requestUri = '', $requestMethod)
    {
        $this->requestUri = $requestUri;
        $this->requestMethod = $requestMethod;
    }

    /**
     * @return Route
     * @throws RouteException
     */
    public function __invoke()
    {
        foreach(self::getRoutes() as $routeName => $route)
        {
            if($this->match($route, $this->requestUri))
            {
                return new Route($routeName, $route, $this->parameters);
            }
        }

        throw new RouteException(sprintf('Route \'%s\' not found', $this->requestUri));
    }

    private function match(array $route, $uri)
    {
        $uri = explode('/', $uri);
        $routeUrlPattern = explode('/', $route['url']);
        $this->parameters = [];

        if(sizeof($routeUrlPattern) !== sizeof($uri))
        {
            return false;
        }

        foreach($uri as $key => $value)
        {
            if($routeUrlPattern[$key] !== $value)
            {
                if(preg_match('/^\[([a-zA-Z0-9]+)\]$/', $routeUrlPattern[$key], $match))
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
                $route['controller'] = preg_replace("/[a-z0-9]+\\\\Controller\\\\(.+)$/i", "$1", $route['controller']);

                self::$routes[$routeName] = $route;
            }
        }

        return self::$routes;
    }

    public static function getCompactRouteName($controller, $action)
    {
        $controller = preg_replace("/[a-z0-9]+\\\\Controller\\\\(.+)$/i", "$1", $controller);

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

        foreach(self::getRoutes() as $routeName => $route)
        {

            if([$controller, $action] === [$route['controller'], $route['action']])
            {
                $relativeUrl = $route['url'];

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