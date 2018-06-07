<?php

namespace Application\Controller\Admin;


use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceInterface;

abstract class Controller
{
    private $serviceContainer;
    private $appender;

    /**
     * Controller constructor.
     * @param ServiceContainer $serviceContainer
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $serviceContainer->getService('appender');
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

    /**
     * @return mixed
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getUser()
    {
        return $this->getService('auth')->getUser();
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