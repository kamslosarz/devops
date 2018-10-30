<?php

namespace Application\EventManager;

use Application\Service\ServiceContainer\ServiceContainer;

interface SubscriberInterface
{
    public function __construct(ServiceContainer $serviceContainer);

    public function getSubscribedEvents(): array;
}