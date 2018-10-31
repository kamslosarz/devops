<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command;
use Application\Console\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Start extends Command
{
    public function execute(): ConsoleResponse
    {
        return $this->addOutput($this->executeInShell('docker-compose up --no-build -d'))->sendOutput();
    }
}