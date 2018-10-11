<?php

namespace Application\Router;

use Application\Config\Config;
use Application\Service\Request\RequestMethods;

class Router
{
    const ROUTE_PARAM_PATTERN = '/[\{|\[]([a-zA-Z0-9]+)[\}|\]]/';
    private static $routes;
    private $requestUri;
    private $parameters;
    private $requestMethod;

    public function __construct($requestUri = '', $requestMethod = RequestMethods::GET)
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
                if(preg_match(self::ROUTE_PARAM_PATTERN, $routeUrlPattern[$key], $match))
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
            self::$routes = Config::get('routes');
        }

        return self::$routes;
    }

    /**
     * @param $name
     * @param array $parameters
     * @return Route|null
     */
    public function getRouteByName($name, $parameters = [])
    {
        $route = isset(self::getRoutes()[$name]) ? self::getRoutes()[$name] : null;

        if(!is_null($route))
        {
            return new Route($name, $route, $parameters);
        }

        return null;
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
            throw new RouteException(sprintf('Route \'%s:%s\' with parameters \'%s\' not found', $controller, $action, implode(',', $parameters)));
        }

        return $relativeUrl;
    }
}