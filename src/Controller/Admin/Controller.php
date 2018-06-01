<?php

namespace Application\Controller\Admin;


use Application\Config\Config;
use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Router\Route;
use Application\Service\Request\Request;
use Application\Service\ServiceInterface;

abstract class Controller
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

        if(!$authService->isAuthenticated() && ($this->getRequest()->getRoute()->getAccess() !== Route::ACCESS_PUBLIC))
        {
            return $this->redirect('Admin\UserController:login');
        }

        if(!$authService->hasAccess() && ($this->getRequest()->getRoute()->getAccess() !== Route::ACCESS_PUBLIC))
        {
            return $this->redirect(Config::get('defaultAction'));
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
     * @return ServiceInterface
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

    /**
     * @return Request
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function getRequest()
    {
        return $this->getService('request');
    }

    /**
     * @param $controller
     * @param array $parameters
     * @param int $code
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function redirect($controller, $parameters = [], $code = 301)
    {
        $route = explode(':', $controller);
        $response = $this->container->getResponse();
        $response->setHeaders([
            sprintf('Location: %s', $this->container
                ->getContext()
                ->getRouter()
                ->getRouteByParameters($route[0], sprintf('%sAction', $route[1]), $parameters))
        ]);

        $this->getRequest()->getSession()->save();
        $this->getRequest()->getCookie()->save();

        return $response();
    }
}