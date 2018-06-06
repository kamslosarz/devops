<?php

namespace Application\Context;

use Application\Config\Config;
use Application\Container\Appender\Appender;
use Application\Container\Appender\AppenderLevel;
use Application\Controller\Controller;
use Application\Response\Response;
use Application\Response\ResponseTypes;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Route;
use Application\Router\RouteException;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessDeniedException;
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
    /** @var Response */
    private $results;

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
     * @throws AccessDeniedException
     * @throws RouteException
     * @throws ServiceContainerException
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    private function dispatch()
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
     * @return Response|ResponseTypes\ErrorResponse|ResponseTypes\RedirectResponse
     * @throws RouteException
     * @throws ServiceContainerException
     * @throws \Application\Router\Dispatcher\DispatcherException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function __invoke()
    {
        if(!$this->results)
        {
            try
            {
                $this->dispatch();
            }
            catch(RouteException $routeException)
            {
                $this->results = new ResponseTypes\ErrorResponse(['exception' => $routeException]);
            }
            catch(AccessDeniedException $accessDeniedException)
            {
                if($this->serviceContainer->getService('accessChecker')->hasAccess(Config::get('defaultAction')))
                {
                    $this->appender->append($accessDeniedException->getMessage(), AppenderLevel::ERROR);
                    $this->results = new ResponseTypes\RedirectResponse(Config::get('defaultAction'));
                }
                else
                {
                    $this->results = new ResponseTypes\RedirectResponse(Config::get('loginAction'));
                }
            }

            switch($this->results->getType())
            {
                case ResponseTypes::CONTEXT_JSON:

                    break;
                case ResponseTypes::REDIRECT:

                    break;
                case ResponseTypes::ERROR:
                    $this->results->setContent($this->executeView($this->results->getParameters(), 'error'));
                    break;
                default:
                    $this->results->setContent($this->executeView($this->results->getParameters(), $this->getViewName($this->results->getRoute())));
                    break;
            }

            $this->serviceContainer->getService('session')->save();
            $this->serviceContainer->getService('cookie')->save();
        }
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

    public function getResults()
    {
        return $this->results;
    }

}