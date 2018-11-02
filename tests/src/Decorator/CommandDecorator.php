<?php

namespace Test\Decorator;

use Application\Console\Command\Command;
use Application\Response\ResponseTypes\ConsoleResponse;

class CommandDecorator extends Command
{
    public function execute(): ConsoleResponse
    {
        return $this->addOutput('Test command invoked')
            ->sendOutput();
    }

    public function executeSetOutput(): ConsoleResponse
    {
        return $this->setOutput('executeSetOutput command invoked')
            ->sendOutput();
    }

    public function executeInShelDecorator($command, $parameters)
    {
        $this->executeInShell($command, $parameters);
    }
}