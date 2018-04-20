<?php

namespace Application\Controller\Admin;


use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Service\AuthService\AuthService;
use Doctrine\ORM\EntityManager;

class Controller
{
    private $container;
    private $appender;

    public function __construct(Container $container, Appender $appender)
    {
        $this->container = $container;
        $this->appender = $appender;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->container->getEntityManager();
    }

    public function addMessage($message, $level)
    {
        $this->appender->append($message, $level);

        return $this;
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws \Application\ServiceContainer\ServiceContainerException
     */
    public function getService($serviceName)
    {
        return $this->container->getServiceContainer()->getService($serviceName);
    }

    public function getUser()
    {
        return $this->getService('authService')->getUser();
    }
}