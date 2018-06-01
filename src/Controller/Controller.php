<?php

namespace Application\Controller;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Response\ResponseTypes;
use Application\Service\ServiceContainer\ServiceContainer;

abstract class Controller
{
    private $serviceContainer;
    private $appender;

    public function __construct(ServiceContainer $serviceContainer, Appender $appender)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $appender;
    }
}