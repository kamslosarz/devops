<?php

namespace Application\Container;


use Application\Context\Context;
use Application\Response\Response;
use Application\Service\Logger\LoggerLevel;
use Application\Service\ServiceContainer\ServiceContainer;
use ErrorHandler\ErrorHandler;

class Container
{
    private $entityManager;
    /** @var Context */
    private $context;
    /** @var ServiceContainer */
    private $serviceContainer;
    private $response;

    public function __construct()
    {
        $this->serviceContainer = new ServiceContainer();
        $this->context = new Context($this->serviceContainer);
    }

    /**
     * @return Response
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __invoke()
    {
        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Executing Context', LoggerLevel::INFO);
        $results = null;

        try
        {
            $this->response = ($this->context)();
        }
        catch(\Exception $e)
        {
            $this->response = (new ErrorHandler($e))();
        }

        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Sending Response, Application is shutting down' . PHP_EOL, LoggerLevel::INFO);

        return $this->response;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function getEntityManager()
    {
        return $this->entityManager;
    }

    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    public function getContext()
    {
        return $this->context;
    }
}