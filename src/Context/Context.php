<?php

namespace Application\Context;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Controller\Controller;
use Application\Factory\Factory;
use Application\Logger\LoggerLevel;
use Application\Response\ResponseTypes;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Route;
use Application\Router\Router;
use Application\View\View;
use Application\View\ViewException;

final class Context
{
    /** @var Controller * */
    private $logger;
    private $container;
    private $route;
    private $appender;
    private $router;

    /**
     * Context constructor.
     * @param Container $container
     * @throws \Application\ServiceContainer\ServiceContainerException
     */
    public function __construct(Container $container)
    {
        $this->logger = $container->getLogger();
        $this->container = $container;
        $this->appender = new Appender($this->container->getServiceContainer()->getService('session'));
    }

    /**
     * @return array|string
     * @throws ContextException
     * @throws \Application\ServiceContainer\ServiceContainerException
     */
    public function __invoke()
    {
        $this->logger->log('IniContext.phptializing Router', LoggerLevel::INFO);
        /** @var Route $route */

        $this->router = new Router($this->container->getServiceContainer()->getService('request')->requestUri());
        $this->route = ($this->router)();

        $this->logger->log('Gathering controller', LoggerLevel::INFO);
        $controller = $this->getControllerFullName($this->route->getController());
        $action = $this->route->getAction();

        $this->logger->log('Validating controller', LoggerLevel::INFO);
        $this->validate($controller, $action);

        $this->logger->log('Dispatching controller', LoggerLevel::INFO);
        $dispatcher = new Dispatcher(Factory::getInstance($controller, [$this->container, $this->appender, $this->router]), $action);
        $dispatcher->dispatch($this->route->getParameters());

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

            throw new ContextException(sprintf('Controller "%s" not exists', $controller));
        }

        if(!method_exists($controller, $action))
        {

            throw new ContextException(sprintf('Action "%s" not exists in %s', $action, $controller));
        }
    }

    public function executeView($results, $viewName)
    {
        try
        {
            if(!$this->isContextJson())
            {
                $this->logger->log('Initializing View', LoggerLevel::INFO);
                $view = new View($results, $this->container);
                $view->setMessages($this->appender->flashMessages());
                $this->logger->log(sprintf('Render View %s', $viewName), LoggerLevel::INFO);
                $results = $view->render($viewName);
            }
            else
            {
                $this->logger->log('Skipping view, context type is json', LoggerLevel::INFO);
                $results = json_encode($results);
            }
        }
        catch(ViewException $e)
        {
            if(!$this->isContextJson())
            {
                $view = new View(['exception' => $e], $this->container);
                $view->setMessages($this->appender->flashMessages());
                $results = $view->render('error');
            }
            else
            {
                $results = ['exception' => $e->getMessage()];
            }
        }

        return $results;
    }

    private function isContextJson()
    {
        return $this->container->getResponse()->getType() === ResponseTypes::CONTEXT_JSON;
    }

    private function getViewName()
    {
        preg_match("/[a-z]+\\\+[a-z]+/", str_replace('controller', '', strtolower($this->route->getController())), $match);
        $namespace = ltrim(str_replace('-action', '', strtolower(preg_replace("/([A-Z])/x", "-$1", $this->route->getAction()))), '-');
        return str_replace('\\', DIRECTORY_SEPARATOR, $match[0]) . DIRECTORY_SEPARATOR . $namespace;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRouter()
    {
        return $this->router;
    }

}