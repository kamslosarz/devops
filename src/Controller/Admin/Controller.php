<?php

namespace Application\Controller\Admin;


use Application\Factory\Factory;
use Application\Router\Router;
use Application\Service\Appender\Appender;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceInterface;
use Application\Service\Translator\Translator;
use function Couchbase\fastlzCompress;

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

    public function addMessage($message, $level)
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
    public function getService($serviceName)
    {
        return $this->serviceContainer->getService($serviceName);
    }

    /**
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getUser()
    {
        return $this->getService('auth')->getUser();
    }

    /**
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getRequest()
    {
        return $this->getService('request');
    }

    /**
     * @return mixed
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getTranslator()
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
    public function getForm($form, $entity = null)
    {
        return Factory::getInstance($form, [$entity, $this->getTranslator(), new Router()]);
    }
}