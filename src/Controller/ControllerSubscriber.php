<?php

namespace Application\Controller;

use Application\EventManager\EventSubscriber;
use Application\Service\ServiceContainer\ServiceContainer;

class ControllerSubscriber extends EventSubscriber
{
    public function __construct(ServiceContainer $serviceContainer)
    {
        parent::__construct($serviceContainer);

        $this->subscribedEvents = $this->serviceContainer->getService('router')->getRoutes();
    }

    public function getSubscribedEvents(): array
    {
        return $this->subscribedEvents;
    }
}