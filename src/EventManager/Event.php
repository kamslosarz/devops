<?php

namespace Application\EventManager;

use Application\ParameterHolder\ParameterHolder;
use Application\Response\Response;
use Application\Service\ServiceContainer\ServiceContainer;

class Event
{
    private $serviceContainer;
    private $response;
    private $parameters = [];

    public function __construct()
    {
        $this->parameters = new ParameterHolder($this->parameters);
    }

    public function getServiceContainer(): serviceContainer
    {
        return $this->serviceContainer;
    }

    public function setServiceContainer(ServiceContainer $serviceContainer): self
    {
        $this->serviceContainer = $serviceContainer;

        return $this;
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getParameters(): ParameterHolder
    {
        return $this->parameters;
    }

    public function setParameters(ParameterHolder $parameterHolder): self
    {
        $this->parameters = $parameterHolder;

        return $this;
    }
}