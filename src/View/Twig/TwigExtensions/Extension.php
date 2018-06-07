<?php

namespace Application\View\Twig\TwigExtensions;

use Application\Service\ServiceContainer\ServiceContainer;

class Extension extends \Twig_Extension
{
    protected $serviceContainer;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function getService($serviceName)
    {
        return $this->serviceContainer->getService($serviceName);
    }
}