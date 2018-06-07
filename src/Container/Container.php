<?php

namespace Application\Container;


use Application\Config\Config;
use Application\Context\Context;
use Application\Response\ResponseTypes;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Router\RouteException;
use Application\Service\AccessChecker\AccessDeniedException;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;

class Container
{
    /** @var Context */
    private $context;
    /** @var ServiceContainer */
    private $serviceContainer;
    private $results;

    /**
     * Container constructor.
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
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
     * @throws \Application\View\ViewException
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
                if($this->serviceContainer->getService('accessChecker')->hasAccess(Config::get('defaultAction')))
                {
                    $this->serviceContainer->getService('appender')->append($accessDeniedException->getMessage(), AppenderLevel::ERROR);
                    $this->results = new RedirectResponse(Config::get('defaultAction'));
                }
                else
                {
                    $this->results = new RedirectResponse(Config::get('loginAction'));
                }
            }

            switch($this->results->getType())
            {
                case ResponseTypes::CONTEXT_JSON:

                    break;
                case ResponseTypes::REDIRECT:

                    break;
                case ResponseTypes::ERROR:
                    $this->results->setContent(
                        $this->view->render(
                            'error',
                            $this->results->getParameters()
                        )
                    );
                    break;
                default:
                    $this->results->setContent(
                        $this->view->render(
                            $this->getViewName($this->results->getRoute()),
                            $this->results->getParameters()
                        )
                    );
                    break;
            }

            $this->serviceContainer->getService('session')->save();
            $this->serviceContainer->getService('cookie')->save();
        }

        return true;
    }

    private function getViewName($route)
    {
        preg_match("/[a-z]+\\\+[a-z]+/", str_replace('controller', '', strtolower($route->getController())), $match);
        $namespace = ltrim(str_replace('-action', '', strtolower(preg_replace("/([A-Z])/x", "-$1", $route->getAction()))), '-');

        return str_replace('\\', DIRECTORY_SEPARATOR, $match[0]) . DIRECTORY_SEPARATOR . $namespace;
    }

    public function getResults()
    {
        return $this->results;
    }

    public function getContext()
    {
        return $this->context;
    }
}