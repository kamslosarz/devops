<?php

namespace Application\Context;

use Application\Controller\Controller;
use Application\Response\Response;
use Application\Router\Dispatcher\ControllerParameters;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Route;
use Application\Router\RouteException;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessDeniedException;
use Application\Service\Appender\Appender;
use Application\Service\Request\Request;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceContainer\ServiceContainerException;

class Context
{
    /** @var ROuter $router * */
    private $router;
    /** @var Appender $appender*/
    private $appender;
    private $serviceContainer;
    /** @var Response */
    private $results;

    /**
     * Context constructor.
     * @param ServiceContainer $serviceContainer
     * @throws ServiceContainerException
     * @throws \Application\Config\ConfigException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = $this->serviceContainer->getService('appender');
        $request = $this->serviceContainer->getService('request');
        $this->router = new Router($request->getRequestUri(), $request->getRequestMethod());
    }

    /**
     * @throws AccessDeniedException
     * @throws RouteException
     * @throws ServiceContainerException
     * @throws \Application\Config\ConfigException
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    public function __invoke()
    {
        /** @var Route $route */
        $route = ($this->router)();

        /** @var Request $request */
        $request = $this->serviceContainer->getService('request');
        $request->setRoute($route);
        $action = $route->getAction();

        if(!$this->serviceContainer->getService('accessChecker')->hasAccess($route))
        {
            throw new AccessDeniedException(sprintf('Access denied to \'%s\'', Router::getRouteUrlByParameters($route->getController(), $route->getAction(), $route->getParameters())));
        }

        $dispatcher = new Dispatcher($route->getController(), $action, [
            $this->serviceContainer, $this->router
        ]);

        $controllerParameters = new ControllerParameters($route->getParameters());
        $controllerParameters->applyAnnotations($route->getController(), $action);
        $dispatcher->dispatch($controllerParameters);

        /** @var Response $response */
        $this->results = $dispatcher->getResponse();
        $this->results->setRoute($route);
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