<?php

namespace Application;

use Application\Config\Config;
use Application\Container\Container;

final class Application
{
    private static $environment = '_dev';
    private $response;

    public function __construct()
    {
        Config::load();
    }

    public function __invoke()
    {
        $container = new Container();
        $this->response = $container();
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