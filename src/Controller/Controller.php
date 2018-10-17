<?php

namespace Application\Controller;


use Application\Factory\Factory;
use Application\Router\Router;
use Application\Service\ServiceContainer\ServiceContainer;

abstract class Controller
{
    private $serviceContainer;
    protected $router;

    /**
     * Controller constructor.
     * @param ServiceContainer $serviceContainer
     * @param Router $router
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer, Router $router)
    {
        $this->serviceContainer = $serviceContainer;
        $this->router = $router;
        $this->appender = $serviceContainer->getService('appender');
    }

    protected function addMessage($message, $level)
    {
        $this->appender->append($message, $level);

        return $this;
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getService($serviceName)
    {
        return $this->serviceContainer->getService($serviceName);
    }

    /**
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getUser()
    {
        return $this->getService('auth')->getUser();
    }

    /**
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getRequest()
    {
        return $this->getService('request');
    }

    /**
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getTranslator()
    {
        return $this->getService('translator');
    }

    /**
     * @param $form
     * @param null $entity
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getForm($form, $entity = null)
    {
        return Factory::getInstance($form, [$entity, $this->getTranslator(), new Router()]);
    }
}