<?php

namespace Application\Service\Config;

use Application\Service\ServiceInterface;

class Config implements ServiceInterface
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function __get($key)
    {
        return $this->config[$key];
    }

    public function __set($key, $value): void
    {
        $this->config[$key] = $value;
    }
}
