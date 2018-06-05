<?php

namespace Application\Console;


use Application\Console\Command\Command;
use Application\Router\Dispatcher\ConsoleDispatcher;
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
     * @return mixed
     * @throws ConsoleException
     * @throws \Application\Router\Dispatcher\DispatcherException
     * @throws \ReflectionException
     */
    public function run()
    {
        /** @var Command $command */
        $command = Command::getCommand($this->consoleParameters->getCommand());

        if(!$command)
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

        $dispatcher = new ConsoleDispatcher($command, 'execute');
        $dispatcher->dispatch($this->consoleParameters->getParameters());

        return $dispatcher->getResponse()->getContent();
    }
}
