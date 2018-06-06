<?php

namespace Application\Controller\Admin;


use Application\Container\Appender\Appender;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceInterface;

abstract class Controller
{
    private $serviceContainer;
    private $appender;

    public function __construct(ServiceContainer $serviceContainer, Appender $appender)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $appender;
    }

    public function addMessage($message, $level)
    {
        $this->appender->append($message, $level);

        return $this;
    }

    /**
     * @param $serviceName
     * @return ServiceInterface
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getService($serviceName)
    {
        return $this->serviceContainer->getService($serviceName);
    }

    public function getUser()
    {
        return $this->sergetService('auth')->getUser();
    }

    /**
     * @return ServiceInterface
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getRequest()
    {
        return $this->getService('request');
    }
}