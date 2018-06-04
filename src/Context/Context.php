<?php

namespace Application\Context;

use Application\Container\Appender\Appender;
use Application\Controller\Controller;
use Application\Response\Response;
use Application\Response\ResponseTypes;
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
    private $response;

    /**
     * Context constructor.testShouldReturnNulledRouter
     * @param ServiceContainer $serviceContainer
     * @throws ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
        $this->appender = new Appender($serviceContainer->getService('session'));
        $this->router = new Router($this->serviceContainer->getService('request')->getRequestUri());
    }

    /**
     * @return Response
     * @throws ServiceContainerException
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    private function dispatch()
    {
        if(!($this->response instanceof Response))
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

            $dispatcher = new Dispatcher($controller, $action, [
                $this->serviceContainer, $this->appender, $this->router
            ]);
            $dispatcher->dispatch($route->getParameters());

            /** @var Response $response */
            $this->response = $dispatcher->getResponse();
            $this->response->setRoute($route);
        }

        return $this->response;
    }

    /**
     * @return Response|ResponseTypes\ErrorResponse
     * @throws ServiceContainerException
     */
    public function __invoke()
    {
        try
        {
            /** @var Response $response */
            $response = $this->dispatch();
        }
        catch(\Exception $routeException)
        {
            /** @var ResponseTypes\ErrorResponse $response */
            $response = new ResponseTypes\ErrorResponse(['exception' => $routeException]);
        }

        switch($response->getType())
        {
            case ResponseTypes::CONTEXT_JSON:

                break;
            case ResponseTypes::REDIRECT:

                break;
            case ResponseTypes::ERROR:
                $response->setContent($this->executeView($response->getParameters(), 'error'));
                break;
            default:
                $response->setContent($this->executeView($response->getParameters(), $this->getViewName($response->getRoute())));
                break;
        }

        $this->serviceContainer->getService('session')->save();
        $this->serviceContainer->getService('cookie')->save();

        return $response;
    }

    private function getControllerFullName($controller)
    {
        return 'Application\\Controller\\' . $controller;
    }

    public function executeView($parameters, $viewName)
    {
        try
        {
            $content = (new View($parameters, $this->serviceContainer))->render($viewName);
        }
        catch(ViewException $e)
        {
            $this->serviceContainer->getService('logger')->log(
                'ApplicationLogger',
                sprintf('View Error: %s', $e->getMessage()),
                LoggerLevel::INFO
            );
            $view = new View(['exception' => $e], $this->serviceContainer);
            $content = $view->render('error');
        }

        return $content;
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