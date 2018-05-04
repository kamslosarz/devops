<?php

namespace Application\Controller\Admin;


use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Controller\ControllerException;
use Application\Router\Router;
use Application\Service\AuthService\AuthService;
use Doctrine\ORM\EntityManager;

class Controller
{
    private $container;
    private $appender;

    /**
     * Controller constructor.
     * @param Container $container
     * @param Appender $appender
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct(Container $container, Appender $appender)
    {
        $this->container = $container;
        $this->appender = $appender;

        $authService = $this->getService('authService');

        if(!$authService->hasAccess())
        {
            return $this->redirect('Admin\UserController:login');
        }
    }

    /**
     * @return EntityManager
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getEntityManager()
    {
        return $this->getService('entityManager')->getEntityManager();
    }

    public function addMessage($message, $level)
    {
        $this->appender->append($message, $level);

        return $this;
    }

    /**
     * @param $serviceName
     * @return mixed
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getService($serviceName)
    {
        return $this->container->getServiceContainer()->getService($serviceName);
    }

    public function getUser()
    {
        return $this->getService('authService')->getUser();
    }

    public function getRequest()
    {
        return $this->getService('request');
    }

    /**
     * @param $controller
     * @param array $parameters
     * @param int $code
     */
    public function redirect($controller, $parameters = [], $code = 301)
    {
        $route = explode(':', $controller);
        $response = $this->container->getResponse();
        $response->setHeaders([
            sprintf('Location: %s', $this->container->getContext()->getRouter()->getRouteByParameters($route[0], sprintf('%sAction', $route[1]), $parameters))
        ]);

        $this->getRequest()->getSession()->save();
        return $response(true);
    }
}