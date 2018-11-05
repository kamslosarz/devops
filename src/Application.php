<?php

namespace Application;

use Application\Container\Container;
use Application\Response\Response;

final class Application
{
    const TEST = '_test';

    private static $environment = '';

    /** @var Response $response */
    private $container = null;

    /**
     * Application constructor.
     * @param $config
     * @throws Service\ServiceContainer\ServiceContainerException
     */
    public function __construct($config)
    {
        self::$environment = $config['environment'];

        $this->container = new Container($config['servicesMap']);
    }

    public function __invoke(): Response
    {
        return ($this->container)()->getResponse();
    }

    public static function getEnvironment()
    {
        return self::$environment;
    }

    public static function setEnvironment($environment): void
    {
        self::$environment = $environment;
    }

    public static function isTest(): bool
    {
        return self::$environment === self::TEST;
    }

    public function setContainer(Container $container): self
    {
        $this->container = $container;

        return $this;
    }
}