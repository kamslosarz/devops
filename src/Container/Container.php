<?php

namespace Application\Container;


use Application\Context\Context;
use Application\Logger\Logger;
use Application\Logger\LoggerLevel;
use Application\Response\Response;
use Application\Service\ServiceContainer\ServiceContainer;

class Container
{
    private $response;
    private $entityManager;
    /** @var Context */
    private $context;
    /** @var ServiceContainer */
    private $serviceContainer;

    public function __construct(Logger $logger = null)
    {
        $this->logger = $logger;

        $this->logger->log('Initializing ServiceContainer', LoggerLevel::INFO);
        $this->serviceContainer = new ServiceContainer();

        $this->logger->log('Initializing context', LoggerLevel::INFO);
        $this->context = new Context($this);

        $this->response = new Response();
    }

    /**
     * @return Response
     * @throws \Application\Logger\LoggerException
     */
    public function __invoke()
    {
        $this->logger->log('Executing Context', LoggerLevel::INFO);
        $results = ($this->context)();

        $this->logger->log('Sending Response, Application is shutting down' . PHP_EOL, LoggerLevel::INFO);
        $this->response->setResults($results);

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