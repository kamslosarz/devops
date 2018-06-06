<?php

namespace Application\Container;


use Application\Context\Context;
use Application\Service\Logger\LoggerLevel;
use Application\Service\ServiceContainer\ServiceContainer;

class Container
{
    /** @var Context */
    private $context;
    /** @var ServiceContainer */
    private $serviceContainer;
    private $results;

    public function __construct()
    {
        $this->serviceContainer = new ServiceContainer();
        $this->context = new Context($this->serviceContainer);
    }

    /**
     * @return mixed
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __invoke()
    {
        if(!$this->results)
        {
            $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Executing Context', LoggerLevel::INFO);
            ($this->context)();
            $this->results = $this->context->getResults();
            $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Sending Response, Application is shutting down' . PHP_EOL, LoggerLevel::INFO);
        }

        return true;
    }

    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getContext()
    {
        return $this->context;
    }
}