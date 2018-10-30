<?php

namespace Application\Service;

use Application\Config\Config;
use Application\ServiceContainer\ServiceResolver;

class ServiceParameters
{
    private $classname;
    private $parameters;

    public function __construct($parameters)
    {
        $this->classname = $parameters[0];
        $this->parameters = $parameters[1];
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getClassname(): string
    {
        return $this->classname;
    }
}