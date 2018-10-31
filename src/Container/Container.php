<?php

namespace Application\Container;


use Application\Controller\ControllerSubscriber;
use Application\EventManager\Event;
use Application\EventManager\EventManager;
use Application\EventManager\EventmanagerException;
use Application\ParameterHolder\ParameterHolder;
use Application\Response\Response;
use Application\Response\ResponseTypes;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Service\Router\Route;
use Application\Service\Router\Router;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;

class Container
{
    /** @var ServiceContainer */
    protected $serviceContainer;
    protected $view;

    /**
     * Container constructor.
     * @param $serviceContainerConfig
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function __construct($serviceContainerConfig)
    {
        $this->serviceContainer = new ServiceContainer($serviceContainerConfig);
        $this->eventManager = new EventManager();
        $this->view = new View($this->serviceContainer);
    }

    /**
     * @return Container
     * @throws \Exception
     */
    public function __invoke(): self
    {
        /** @var Event $event */
        $event = new Event();
        try
        {
            /** @var Router $router */
            $router = $this->serviceContainer->getService('router');
            $event->setParameters(new ParameterHolder($router->getParameters()))
                ->setServiceContainer($this->serviceContainer);

            $this->eventManager->addSubscriber(new ControllerSubscriber($this->serviceContainer));
            $route = $router->getRoute();

            if(!($route instanceof Route))
            {
                throw new EventmanagerException('Route not exists');
            }

            $this->eventManager->dispatch($route->getName(), $event);
            $this->response = $event->getResponse();
        }
        catch(\Exception $e)
        {
            $this->response = new ErrorResponse(['exception' => $e]);

            throw $e;
        }

        switch($this->response->getType())
        {
            case ResponseTypes::JSON:
                $this->response->setContent($this->response->getJson());
                break;
            case ResponseTypes::ERROR:
                $this->response->setContent($this->view->render($this->response));
                break;
            case ResponseTypes::REDIRECT:
                break;
            default:
                $this->response->setContent($this->view->render($this->response));
                break;
        }

        return $this;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }
}