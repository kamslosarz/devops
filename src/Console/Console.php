<?php

namespace Application\Console;

use Application\EventManager\Event;
use Application\EventManager\EventManager;
use Application\Response\Response;
use Application\Service\ServiceContainer\ServiceContainer;


class Console
{
    private $consoleParameters;
    private $serviceContainer;

    public function __construct(ConsoleParameters $consoleParameters, array $serviceContainerConfig)
    {
        $this->consoleParameters = $consoleParameters;
        $this->serviceContainer = new ServiceContainer($serviceContainerConfig);
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @doesNotPerformAssertions
     */
    public function __invoke(): Response
    {
        $eventManager = new EventManager();
        $commandSubscriber = new CommandSubscriber($this->serviceContainer);
        $eventManager->addSubscriber($commandSubscriber);
        $event = (new Event())
            ->setParameters($this->consoleParameters->getCommandParameters());

        $eventManager->dispatch($this->consoleParameters->getCommand(), $event);

        return $event->getResponse();
    }
}
