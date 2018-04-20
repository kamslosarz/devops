<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;
use Application\Logger\Logger;
use Application\Logger\LoggerLevel;
use Application\Response\Response;
use Application\Response\ResponseCodes;
use Application\View\View;
use Application\View\ViewException;

final class Application
{
    private $router;
    private $logger;
    private $applicationParameters;


    public function __construct(ApplicationParameters $applicationParameters)
    {
        Config::load();

        $this->applicationParameters = $applicationParameters;
        $this->logger = new Logger('ApplicationLogger');
    }

    /**
     * @return mixed
     * @throws Context\ContextException
     * @throws Router\RouteException
     * @throws ViewException
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke()
    {
        $this->logger->log('Initializing Application', LoggerLevel::INFO);

        $container = new Container($this->logger, $this->applicationParameters);
        $container();
        $response = $container->getResponse();

        return $response();

    }
}