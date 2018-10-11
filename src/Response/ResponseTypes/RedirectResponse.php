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
     */
    public function __construct($redirect, $parameters = [])
    {
        $this->setType(ResponseTypes::REDIRECT);
        $this->setHeaders([sprintf('Location: %s', $redirect)]);

        parent::__construct($parameters);
    }
}