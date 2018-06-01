<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;
use Application\Logger\Logger;
use Application\Logger\LoggerLevel;

final class Application
{
    private static $environment = '_dev';
    private $logger;

    public function __construct()
    {
        Config::load();

        $this->logger = new Logger('ApplicationLogger');
    }

    public function __invoke()
    {
        $this->logger->log('Initializing Application', LoggerLevel::INFO);
        $container = new Container($this->logger);
        $container();

        return $container->getResponse();
    }

    public static function getEnvironment()
    {
        return self::$environment;
    }

    public static function setProduction()
    {
        self::$environment = null;
    }
}