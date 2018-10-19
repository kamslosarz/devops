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
    protected $response;
    protected $view;

    /**
     * Container constructor.
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
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function __invoke()
    {
        if(!$this->response)
        {
            try
            {
                ($this->context)();
                $this->response = $this->context->getResponse();
            }
            catch(RouteException $routeException)
            {
                $this->response = new ErrorResponse(['exception' => $routeException]);
            }
            catch(AccessDeniedException $accessDeniedException)
            {
                $route = $this->context->getRouter()->getRouteByName(Config::get('defaultAction'));

                if($this->serviceContainer->getService('accessChecker')->hasAccess($route))
                {
                    $this->serviceContainer->getService('appender')->append($accessDeniedException->getMessage(), AppenderLevel::ERROR);
                    $this->response = new RedirectResponse($this->context->getRouter()->getRouteByName(Config::get('defaultAction'))->getUrl());
                }
                else
                {
                    $this->response = new RedirectResponse($this->context->getRouter()->getRouteByName(Config::get('loginAction'))->getUrl());
                }
            }

            switch($this->response->getType())
            {
                case ResponseTypes::JSON:
                    $this->response->setContent($this->response->getJson());
                    break;
                case ResponseTypes::ERROR:
                    $this->response->setContent($this->view->render(new ViewElement(
                        'error',
                        $this->response->getParameters()
                    )));
                    break;
                case ResponseTypes::REDIRECT:

                    break;
                default:
                    $this->response->setContent($this->view->render(new ViewElement(
                        $this->response->getRoute(),
                        $this->response->getParameters()
                    )));
                    break;
            }
        }

        return true;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
    }

    public function getContext()
    {
        return $this->context;
    }
}