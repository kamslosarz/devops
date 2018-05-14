<?php

namespace Application\Console;


use Application\Console\Command\Command;
use Application\Router\Dispatcher\Dispatcher;

class Console
{
    private $consoleParameters;

    public function __construct(ConsoleParameters $consoleParameters)
    {
        $this->consoleParameters = $consoleParameters;
    }

    /**
     * @throws ConsoleException
     * @throws \ReflectionException
     */
    public function run()
    {
        /** @var Command $command */
        $command = Command::getInstance($this->consoleParameters->getCommand());

        if(!$command)
        {
            throw new ConsoleException('Command not found');
        }

        $action = $this->consoleParameters->getAction();

        if(!method_exists($command, $action))
        {
            throw new ConsoleException('Invalid action');
        }

        if(count($this->consoleParameters->getParameters()) < (new \ReflectionMethod($command, $action))->getNumberOfRequiredParameters())
        {
            throw new ConsoleException('Invalid number of parameters');
        }

        if(!$command->isValid(...$this->consoleParameters->getParameters()))
        {
            throw new ConsoleException(implode(PHP_EOL, $command->getErrors()));
        }

        $dispatcher = new Dispatcher($command, $action);
        $dispatcher->dispatch($this->consoleParameters->getParameters());

        return $dispatcher->getResults();
    }
}