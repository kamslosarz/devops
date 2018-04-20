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
     */
    public function run()
    {
        /** @var Command $command */
        $command = Command::getInstance($this->consoleParameters->getCommand());

        if (!$command) {

            throw new ConsoleException('Command not found');
        }

        $action = $this->consoleParameters->getAction();

        if(!$command->isValid()){

            throw new ConsoleException('Invalid arguments');
        }

        if(!method_exists($command, $action)){

            throw new ConsoleException('Invalid action');
        }

        $dispatcher = new Dispatcher($command, $action);
        $dispatcher->dispatch(...$this->consoleParameters->getParameters());

        return $dispatcher->getResults();
    }
}