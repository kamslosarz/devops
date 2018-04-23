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

    public function __construct()
    {
        Config::load();

        $this->logger = new Logger('ApplicationLogger');
    }

    /**
     * @throws Context\ContextException
     * @throws ServiceContainer\ServiceContainerException
     * @throws \Doctrine\ORM\ORMException
     */
    public function __invoke()
    {
        $this->logger->log('Initializing Application', LoggerLevel::INFO);

        $container = new Container($this->logger);
        $container();
        $response = $container->getResponse();

        return $response();

    }
}