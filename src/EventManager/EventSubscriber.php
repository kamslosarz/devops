<?php

namespace Application\EventManager;

use Application\Service\ServiceContainer\ServiceContainer;

abstract class EventSubscriber implements SubscriberInterface
{
    protected $serviceContainer;
    protected $subscribedEvents;

    public function __construct(ServiceContainer $serviceContainer)
    {
        $this->serviceContainer = $serviceContainer;
    }

    public function getSubscribedEvents(): array
    {
        return $this->subscribedEvents;
    }
}