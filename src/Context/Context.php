<?php

namespace Application\Context;

use Application\Container\Appender\Appender;
use Application\Controller\Controller;
use Application\Factory\Factory;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Route;
use Application\Router\Router;
use Application\Service\Logger\LoggerLevel;
use Application\Service\Request\Request;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceContainer\ServiceContainerException;
use Application\View\View;
use Application\View\ViewException;

class Context
{
    /** @var Controller * */
    private $router;
    private $appender;
    private $serviceContainer;

    /**
     * Context constructor.
     * @param ServiceContainer $serviceContainer
     * @throws ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = new Appender($serviceContainer->getService('session'));
        $this->router = new Router($this->serviceContainer->getService('request')->requestUri());
    }

    /**
     * @return array|string
     * @throws ContextException
     * @throws ServiceContainerException
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
        $this->validate($controller, $action);

        $this->serviceContainer->getService('logger')->log('ApplicationLogger', 'Dispatching controller', LoggerLevel::INFO);
        $dispatcher = new Dispatcher(Factory::getInstance($controller, [
            $this->serviceContainer, $this->appender, $this->router
        ]), $action);
        $dispatcher->dispatch($route->getParameters());

        return $this->executeView($dispatcher->getResults(), $this->getViewName($route));
    }

    private function getControllerFullName($controller)
    {
        return 'Application\\Controller\\' . $controller;
    }

    /**
     * @param $controller
     * @param $action
     * @throws ContextException
     */
    private function validate($controller, $action)
    {
        if(!class_exists($controller))
        {
            throw new ContextException(sprintf('Controller \'%s\' not exists', $controller));
        }

        if(!method_exists($controller, $action))
        {
            throw new ContextException(sprintf('Action \'%s\' not exists in %s', $action, $controller));
        }
    }

    public function executeView($results, $viewName)
    {
        try
        {
            $results = (new View($results, $this->serviceContainer))->render($viewName);
        }
        catch(ViewException $e)
        {
            $this->serviceContainer->getService('logger')->log(
                'ApplicationLogger',
                sprintf('View Error: %s', $e->getMessage()),
                LoggerLevel::INFO
            );
            $view = new View(['exception' => $e], $this->serviceContainer);
            $results = $view->render('error');
        }

        return $results;
    }

    private function getViewName($route)
    {
        preg_match("/[a-z]+\\\+[a-z]+/", str_replace('controller', '', strtolower($route->getController())), $match);
        $namespace = ltrim(str_replace('-action', '', strtolower(preg_replace("/([A-Z])/x", "-$1", $route->getAction()))), '-');
        return str_replace('\\', DIRECTORY_SEPARATOR, $match[0]) . DIRECTORY_SEPARATOR . $namespace;
    }

    public function getRouter()
    {
        return $this->router;
    }

    public function getAppender()
    {
        return $this->appender;
    }

}