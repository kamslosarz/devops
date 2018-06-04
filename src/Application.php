<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;
use Application\Response\Response;

final class Application
{
    private static $environment = '_dev';
    /** @var Response $response */
    private $response = null;

    public function __construct()
    {
        Config::load();
    }

    public function __invoke()
    {
        if(is_null($this->response))
        {
            $container = new Container();
            $this->response = $container();
        }

        return $this->response;
    }

    public function getResponse()
    {
        return $this->response;
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