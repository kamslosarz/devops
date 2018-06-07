<?php

namespace Application\Controller;

use Application\Container\Appender\Appender;
use Application\Service\ServiceContainer\ServiceContainer;

abstract class Controller
{
    private $serviceContainer;
    private $appender;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $serviceContainer->getService('appender');
    }
}