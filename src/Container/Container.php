<?php

namespace Application\Container;


use Application\Config\Config;
use Application\Context\Context;
use Application\Response\Response;
use Application\Response\ResponseTypes;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Router\RouteException;
use Application\Router\Router;
use Application\Service\AccessChecker\AccessDeniedException;
use Application\Service\Appender\AppenderLevel;
use Application\Service\Request\RequestMethods;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;
use Application\View\ViewElement;

class Container
{
    /** @var Context */
    protected $context;
    /** @var ServiceContainer */
    protected $serviceContainer;
    protected $results;
    protected $view;

    /**
     * Container constructor.
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function __construct()
    {
        $this->serviceContainer = new ServiceContainer();
        $this->context = new Context($this->serviceContainer);
        $this->view = new View($this->serviceContainer);
    }

    /**
     * @return bool
     * @throws RouteException
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function __invoke()
    {
        if(!$this->results)
        {
            try
            {
                ($this->context)();
                $this->results = $this->context->getResults();
            }
            catch(RouteException $routeException)
            {
                $this->results = new ErrorResponse(['exception' => $routeException]);
            }
            catch(AccessDeniedException $accessDeniedException)
            {
                $route = $this->context->getRouter()->getRouteByName(Config::get('defaultAction'));

                if($this->serviceContainer->getService('accessChecker')->hasAccess($route))
                {
                    $this->serviceContainer->getService('appender')->append($accessDeniedException->getMessage(), AppenderLevel::ERROR);
                    $this->results = new RedirectResponse($this->context->getRouter()->getRouteByName(Config::get('defaultAction'))->getUrl());
                }
                else
                {
                    $this->results = new RedirectResponse($this->context->getRouter()->getRouteByName(Config::get('loginAction'))->getUrl());
                }
            }

            switch($this->results->getType())
            {
                case ResponseTypes::JSON:
                    $this->results->setContent($this->results->getJson());
                    break;
                case ResponseTypes::ERROR:
                    $this->results->setContent($this->view->render(new ViewElement(
                        'error',
                        $this->results->getParameters()
                    )));
                    break;
                case ResponseTypes::REDIRECT:

                    break;
                default:

                    $this->results->setContent($this->view->render(new ViewElement(
                        $this->results->getRoute(),
                        $this->results->getParameters()
                    )));

                    break;
            }

            $this->serviceContainer->getService('session')->save();
            $this->serviceContainer->getService('cookie')->save();
        }

        return true;
    }

    /**
     * @return Response
     */
    public function getResults()
    {
        return $this->results;
    }

    public function getContext()
    {
        return $this->context;
    }
}