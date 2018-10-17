<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;
use Application\Context\Context;
use Application\Response\Response;

final class Application
{
    const TEST = '_test';

    private static $environment = '';

    /** @var Response $response */
    private $results = null;
    private $container = null;

    public function __construct($environment = '')
    {
        self::$environment = $environment;
        Config::load();

        $this->container = new Container();
    }

    public function __invoke()
    {
        if(is_null($this->results))
        {
            ($this->container)();
            $this->results = $this->container->getResults();
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

    public function setContainer(Container $container)
    {
        $this->container = $container;

        return $this;
    }
}