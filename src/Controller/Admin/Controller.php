<?php

namespace Application\Controller\Admin;


use Application\Container\Appender\Appender;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceInterface;

abstract class Controller
{
    private $serviceContainer;
    private $appender;

    /**
     * Controller constructor.
     * @param ServiceContainer $serviceContainer
     * @param Appender $appender
     */
    public function __construct(ServiceContainer $serviceContainer, Appender $appender)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $appender;
    }

    /**
     * @param $name
     * @param $arguments
     * @return RedirectResponse
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function __call($name, $arguments)
    {
        $authService = $this->getService('authService');

        if(!$authService->isAuthenticated() && ($this->getRequest()->getRoute()->getAccess() !== Route::ACCESS_PUBLIC))
        {
            return new RedirectResponse('Admin\UserController:login');
        }

        if(!$authService->hasAccess() && ($this->getRequest()->getRoute()->getAccess() !== Route::ACCESS_PUBLIC))
        {
            return new RedirectResponse($this->redirect(Config::get('defaultAction')));
        }
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
        return $this->sergetService('authService')->getUser();
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