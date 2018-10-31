<?php

namespace Application\Console\Command;

use Application\EventManager\EventSubscriber;
use Application\Service\ServiceContainer\ServiceContainer;

class CommandSubscriber extends EventSubscriber
{
    /**
     * CommandSubscriber constructor.
     * @param ServiceContainer $serviceContainer
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function __construct(ServiceContainer $serviceContainer)
    {
        parent::__construct($serviceContainer);

        $this->subscribedEvents = $serviceContainer->getService('commandRouter')->getRoutes();
    }
}