<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Stop extends Command
{
    public function execute(CommandParameters $commandParameters): ConsoleResponse
    {
        return $this->setOutput($this->executeInShell('docker-compose down'))->sendOutput();
    }
}