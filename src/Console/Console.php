<?php

namespace Application\Console;

use Application\Console\Command\CommandSubscriber;
use Application\EventManager\Event;
use Application\EventManager\EventManager;
use Application\Response\Response;
use Application\Response\ResponseTypes\ConsoleResponse;
use Application\Service\ServiceContainer\ServiceContainer;

class Console
{
    private $consoleParameters;
    private $serviceContainer;

    public function __construct(ConsoleParameters $consoleParameters, $servicesMap)
    {
        $this->consoleParameters = $consoleParameters;
        $this->serviceContainer = new ServiceContainer($servicesMap);
    }

    /**
     * @return Response
     * @throws ConsoleException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @doesNotPerformAssertions
     */
    public function __invoke(): ConsoleResponse
    {
        $eventManager = new EventManager();
        $commandSubscriber = new CommandSubscriber($this->serviceContainer);
        $eventManager->addSubscriber($commandSubscriber);
        $command = $this->consoleParameters->getCommand();
        $event = (new Event())
            ->setParameters($this->consoleParameters->getCommandParameters())
            ->setServiceContainer($this->serviceContainer);

        if(!$eventManager->hasEvent($command))
        {
            throw new ConsoleException('Command not found');
        }

        $eventManager->dispatch($command, $event);

        return $event->getResponse();
    }
}
