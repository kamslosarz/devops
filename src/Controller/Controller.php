<?php

namespace Application\Controller;


use Application\EventManager\Event;
use Application\EventManager\EventListenerInterface;
use Application\Factory\Factory;
use Application\Form\Form;
use Application\Service\AuthService\AuthService;
use Application\Service\Request\Request;
use Application\Service\Router\Router;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\ServiceContainer\ServiceResolver;
use Application\Service\ServiceInterface;
use Application\Service\Translator\Translator;
use Model\User;

abstract class Controller implements EventListenerInterface
{
    /** @var $serviceContainer ServiceContainer */
    private $serviceContainer;
    protected $router;

    /**
     * Controller constructor.
     * @param Event $event
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct(Event $event)
    {
        $this->serviceContainer = $event->getServiceContainer();
        $this->appender = $this->serviceContainer->getService('appender');
    }

    protected function addMessage($message, $level): self
    {
        $this->appender->append($message, $level);

        return $this;
    }

    /**
     * @param $serviceName
     * @return ServiceInterface
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getService($serviceName): ServiceInterface
    {
        return $this->serviceContainer->getService($serviceName);
    }

    /**
     * @return User
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getUser(): User
    {
        return $this->getService('auth')->getUser();
    }

    /**
     * @return Request
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getRequest(): Request
    {
        return $this->getService('request');
    }

    /**
     * @param $form
     * @param null $entity
     * @return Form
     */
    protected function getForm($form, $entity = null): Form
    {
        return Factory::getInstance($form, [$entity, $this->serviceContainer]);
    }

    /**
     * @return Translator
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getTranslator(): Translator
    {
        return $this->getService('translator');
    }

    /**
     * @return Router
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    protected function getRouter(): Router
    {
        return $this->getService('router');
    }
}