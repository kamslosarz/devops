<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;
use Application\Response\Response;

final class Application
{
    const TEST = '_test';

    private static $environment = '';
    /** @var Response $response */
    private $results = null;

    public function __construct($environment = '')
    {
        self::$environment = $environment;
        Config::load();
    }

    public function __invoke()
    {
        if(is_null($this->results))
        {
            $container = new Container();
            $container();
            $this->results = $container->getResults();
        }

        return $this->results;
    }

    public function getResults()
    {
        return $this->results;
    }

    public static function getEnvironment()
    {
        return self::$environment;
    }

    public static function setEnvironment($environment)
    {
        self::$environment = $environment;
    }

    public static function isTest()
    {
        return self::$environment === self::TEST;
    }
}