<?php

namespace Application\Console;

use Application\Console\Command\Command\CommandParameters;
use Application\ParameterHolder\ParameterHolder;

class ConsoleParameters extends ParameterHolder
{
    private $command;
    private $commandParameters;

    public function __construct(array $parameters = [])
    {
        if(isset($parameters[1]))
        {
            $this->command = $parameters[1];

            unset($parameters[0]);
            unset($parameters[1]);
        }

        parent::__construct(array_values($parameters));
    }

    public function getCommand(): string
    {
        return $this->command;
    }

    public function getCommandParameters(): CommandParameters
    {
        if(!($this->commandParameters instanceof CommandParameters))
        {
            $this->commandParameters = new CommandParameters($this->parameters);
        }

        return $this->commandParameters;
    }
}