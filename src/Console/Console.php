<?php

namespace Application\Console;


use Application\Console\Command\Command;
use Application\Console\Command\CommandException;
use Application\Router\Dispatcher\Dispatcher;
use Application\Service\ServiceContainer\ServiceContainer;

class Console
{
    private $consoleParameters;
    private $serviceContainer;

    public function __construct(ConsoleParameters $consoleParameters)
    {
        $this->consoleParameters = $consoleParameters;
        $this->serviceContainer = new ServiceContainer();
    }

    /**
     * @throws CommandException
     * @throws ConsoleException
     * @throws \ReflectionException
     */
    public function run()
    {
        /** @var Command $command */
        
        $command = Command::getInstance($this->consoleParameters->getCommand());

        if(!($command instanceof Command))
        {
            throw new ConsoleException('Command not found');
        }

        if(count($this->consoleParameters->getParameters()) < (new \ReflectionMethod($command, 'execute'))->getNumberOfRequiredParameters())
        {
            throw new ConsoleException('Invalid number of parameters');
        }

        if(!$command->isValid(...$this->consoleParameters->getParameters()))
        {
            throw new ConsoleException(implode(PHP_EOL, $command->getErrors()));
        }

        $dispatcher = new Dispatcher($command, 'execute');
        $dispatcher->dispatch($this->consoleParameters->getParameters());

        return $dispatcher->getResults();
    }
}