<?php

namespace Application\Controller;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Response\ResponseTypes;

abstract class Controller
{
    private $container;
    private $appender;

    public function __construct(Container $container, Appender $appender)
    {
        $this->container = $container;
        $this->appender = $appender;
    }

    public function setContextJson()
    {
        $this->container->getResponse()->setType(ResponseTypes::CONTEXT_JSON);

        return $this;
    }

    public function setContextHtml()
    {
        $this->container->getResponse()->setType(ResponseTypes::CONTEXT_HTML);

        return $this;
    }

    public function setResponseHeaders($headers = [])
    {
        $this->container->getResponse()->setHeaders($headers);

        return $this;
    }
}