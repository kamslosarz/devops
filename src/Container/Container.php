<?php

namespace Application\Container;


use Application\ApplicationParameters;
use Application\Config\Config;
use Application\Context\Context;
use Application\Logger\Logger;
use Application\Logger\LoggerLevel;
use Application\Response\Response;
use Application\ServiceContainer\ServiceContainer;
use Application\Session\Session;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

class Container
{
    private $logger;
    private $applicationParameters;
    private $response;
    private $entityManager;
    private $session;
    /** todo MOVE appender TO context / controller */
    private $appender;
    /** @var Context */
    private $context;
    /** @var ServiceContainer */
    private $serviceContainer;

    public function __construct(Logger $logger = null, ApplicationParameters $applicationParameters = null)
    {
        $this->logger = $logger;
        $this->applicationParameters = $applicationParameters;
        $this->session = new Session();
        $this->response = new Response();
    }

    /**
     * @throws \Application\Context\ContextException
     * @throws \Application\Router\RouteException
     * @throws \Application\View\ViewException
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke()
    {
        $this->logger->log('Initializing orm', LoggerLevel::INFO);
        $doctrineConfig = Config::get('doctrine');
        $config = Setup::createAnnotationMetadataConfiguration(array($doctrineConfig['models']), true);
        $this->entityManager = EntityManager::create($doctrineConfig, $config);

        $this->logger->log('Initializing ServiceContainer', LoggerLevel::INFO);
        $this->serviceContainer = new ServiceContainer();

        $this->logger->log('Initializing context', LoggerLevel::INFO);
        /** @var Context $context */
        $this->context = new Context($this);

        $this->logger->log('Executing Context', LoggerLevel::INFO);
        $context = $this->context;
        $results = $context($this->applicationParameters);

        $this->logger->log('Sending Response, Application is shutting down' . PHP_EOL, LoggerLevel::INFO);
        $this->response->setResults($results);

        return $this->response;
    }

    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
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

    public function setSession(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getApplicationParameters()
    {
        return $this->applicationParameters;
    }

    public function getServiceContainer()
    {
        return $this->serviceContainer;
    }

    public function getRequest()
    {
        return $this->applicationParameters->getRequest();
    }

}