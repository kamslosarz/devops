<?php

namespace Application\Response\ResponseTypes;

use Application\Response\Response;
use Application\Response\ResponseTypes;
use Application\Router\Route;
use Application\Router\Router;
use Response\ResponseTypes\RedirectResponseException;

class RedirectResponse extends Response
{
    /**
     * RedirectResponse constructor.
     * @param $redirect
     * @param array $parameters
     * @throws RedirectResponseException
     * @throws \Application\Router\RouteException
     */
    public function __construct($redirect, $parameters = [])
    {
        $this->setType(ResponseTypes::REDIRECT);
        /** @var Route $route */
        $route = Router::getRouteByname($redirect);
        $location = Router::getRouteUrlByParameters($route->getController(), $route->getAction(), $parameters);

        $this->setHeaders([sprintf('Location: %s', $location)]);
    }
}