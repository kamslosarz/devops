<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\ConsoleExecutable;

class Stop extends ConsoleExecutable
{
    public function execute(CommandParameters $commandParameters)
    {
        $this->executeInShell('docker-compose down');
    }

    public function isValid(CommandParameters $commandParameters)
    {
        return true;
    }
}