<?php

namespace Application\Response\ResponseTypes;

use Application\Response\Response;
use Application\Response\ResponseTypes;
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
        $route = explode(':', $redirect);
        $location = Router::getRouteUrlByParameters($route[0], sprintf('%sAction', $route[1]), $parameters);
        $this->setHeaders([sprintf('Location: %s', $location)]);
    }
}