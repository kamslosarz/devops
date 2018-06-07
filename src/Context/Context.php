<?php

namespace Application\Context;

use Application\Controller\Controller;
use Application\Response\Response;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Route;
use Application\Router\RouteException;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessDeniedException;
use Application\Service\Logger\LoggerLevel;
use Application\Service\Request\Request;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceContainer\ServiceContainerException;

class Context
{
    /** @var Controller * */
    private $router;
    private $appender;
    private $serviceContainer;
    /** @var Response */
    private $results;

    /**
     * Context constructor.
     * @param ServiceContainer $serviceContainer
     * @throws ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $this->serviceContainer->getService('appender');
        $this->router = new Router($this->serviceContainer->getService('request')->getRequestUri());
    }

    /**
     * @throws AccessDeniedException
     * @throws RouteException
     * @throws ServiceContainerException
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    public function __invoke()
    {
        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Initializing Router', LoggerLevel::INFO);

        /** @var Route $route */
        $route = ($this->router)();

        /** @var Request $request */
        $request = $this->serviceContainer->getService('request');
        $request->setRoute($route);

        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Gathering controller', LoggerLevel::INFO);
        $controller = $this->getControllerFullName($route->getController());
        $action = $route->getAction();

        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Validating controller', LoggerLevel::INFO);
        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Dispatching controller', LoggerLevel::INFO);

        if(!$this->serviceContainer->getService('accessChecker')->hasAccess())
        {
            throw new AccessDeniedException(sprintf('Access denied to \'%s\'', Router::getRouteByParameters($route->getController(), $route->getAction(), $route->getParameters())));
        }

        $dispatcher = new Dispatcher($controller, $action, [
            $this->serviceContainer, $this->appender, $this->router
        ]);
        $dispatcher->dispatch($route->getParameters());

        /** @var Response $response */
        $this->results = $dispatcher->getResponse();
        $this->results->setRoute($route);
    }

    /**
     * @param $controller
     * @return string
     */
    private function getControllerFullName($controller)
    {
        return 'Application\\Controller\\' . $controller;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getAppender()
    {
        return $this->appender;
    }

    public function getResults()
    {
        return $this->results;
    }

}