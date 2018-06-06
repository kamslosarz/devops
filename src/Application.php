<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;
use Application\Response\Response;

final class Application
{
    private static $environment = '_dev';
    /** @var Response $response */
    private $results = null;

    public function __construct()
    {
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

    public static function setProduction()
    {
        self::$environment = null;
    }
}