<?php

namespace Application\Console;


use Application\Console\Command\Command;
use Application\Router\Dispatcher\ConsoleDispatcher;
use Application\Service\ServiceContainer\ServiceContainer;

class Console
{
    /** @var ConsoleParameters $consoleParameters */
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

        if(is_null($command))
        {
            throw new ConsoleException(sprintf('Command %s not found', $this->consoleParameters->getCommand()));
        }

        if(!$command->isValid($this->consoleParameters->getCommandParameters()))
        {
            throw new ConsoleException(implode(PHP_EOL, $command->getErrors()));
        }

        $dispatcher = new ConsoleDispatcher($command, 'execute');
        $dispatcher->dispatch($this->consoleParameters);

        return $dispatcher->getResponse()->getContent();
    }
}
