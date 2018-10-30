<?php

namespace Application\View\Twig\TwigExtensions;

use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceInterface;

class Extension extends \Twig_Extension
{
    protected $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function getService($serviceName): ServiceInterface
    {
        return $this->serviceContainer->getService($serviceName);
    }
}