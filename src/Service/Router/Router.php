<?php

namespace Application\Service\Router;

use Application\Service\Request\Request;
use Application\Service\ServiceInterface;
use Service\Router\RouterException;

class Router implements ServiceInterface
{
    const ROUTE_PARAM_PATTERN = '/[\{|\[]([a-zA-Z0-9]+)[\}|\]]/';

    private $request;
    private $routes;
    private $route = null;
    private $parameters = [];

    public function __construct(Request $request, array $routes)
    {
        $this->request = $request;
        $this->routes = $routes;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function getRoute(): Route
    {
        $requestUri = $this->request->getRequestUri();
        $requestParameters = array_filter(explode('/', $requestUri));

        foreach($this->routes as $name => $route)
        {
            $this->parameters = [];
            $routeParameters = array_filter(explode('/', $name));

            if(sizeof($routeParameters) === sizeof($requestParameters))
            {
                foreach($routeParameters as $id => $parameter)
                {
                    if($parameter !== $requestParameters[$id])
                    {
                        if(!preg_match(self::ROUTE_PARAM_PATTERN, $parameter))
                        {
                            continue 2;
                        }

                        $this->parameters[] = $requestParameters[$id];
                    }
                }

                $this->route = new Route($name, $route);

                break;
            }
        }

        return $this->route;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    private function hasRoute($name): bool
    {
        return isset($this->routes[$name]);
    }

    /**
     * @param $route
     * @param $parameters
     * @return string
     * @throws RouterException
     */
    public function getUrl($route, $parameters): string
    {
        if($this->hasRoute($route))
        {
            $route = explode('/', $route);

            foreach($route as $id => $fragment)
            {
                if(preg_match(self::ROUTE_PARAM_PATTERN, $fragment, $match))
                {
                    if(isset($parameters[$match[1]]))
                    {
                        $route[$id] = $parameters[$match[1]];
                    }
                }
            }
        }

        return implode('/', $route);
    }
}