<?php

namespace Test\Decorator;


use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class CommandDecorator extends Command
{
    public function execute(CommandParameters $commandParameters): ConsoleResponse
    {
        return $this->addOutput('Test command invoked')->sendOutput();
    }
}