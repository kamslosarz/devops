<?php

namespace Test\Decorator;

use Application\Service\ServiceInterface;

class ServiceDecorator implements ServiceInterface
{
    public $parameters = [];

    public function __construct($parameters)
    {
        $this->parameters = $parameters;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}