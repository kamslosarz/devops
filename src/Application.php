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
     * @param $environment
     * @param $serviceContainerConfig
     * @throws Service\ServiceContainer\ServiceContainerException
     * @throws View\Twig\TwigFactoryException
     */
    public function __construct($environment, $serviceContainerConfig)
    {
        self::$environment = $environment;

        $this->container = new Container($serviceContainerConfig);
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