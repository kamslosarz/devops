<?php

namespace Application\Service\ServiceContainer;

use Application\Factory\Factory;
use Application\Service\ServiceInterface;

class ServiceResolver
{
    private $serviceClass;
    private $serviceParameters;

    public function __construct($service, $serviceParameters)
    {
        $this->serviceClass = $service;
        $this->serviceParameters = $serviceParameters;
    }

    public function __invoke(): ServiceInterface
    {
        return Factory::getInstance($this->serviceClass, $this->serviceParameters);
    }
}