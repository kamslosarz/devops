<?php

namespace Application\Container;


use Application\Config\Config;
use Application\Context\Context;
use Application\Logger\Logger;
use Application\Logger\LoggerLevel;
use Application\Response\Response;
use Application\ServiceContainer\ServiceContainer;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

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
        /** TODO Move To services logger */
        $this->logger = $logger;

        $this->logger->log('Initializing ServiceContainer', LoggerLevel::INFO);
        $this->serviceContainer = new ServiceContainer();

        $this->logger->log('Initializing context', LoggerLevel::INFO);
        $this->context = new Context($this);

        /** TODO Move To services logger */
        $this->response = new Response();
    }

    /**
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke()
    {
        $this->logger->log('Initializing orm', LoggerLevel::INFO);
        $doctrineConfig = Config::get('doctrine');
        $config = Setup::createAnnotationMetadataConfiguration(array($doctrineConfig['models']), true);
        $this->entityManager = EntityManager::create($doctrineConfig, $config);

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