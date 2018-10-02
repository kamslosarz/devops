<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\ConsoleExecutable;

class Start extends ConsoleExecutable
{
    public function execute(CommandParameters $commandParameters)
    {
        $this->executeInShell('docker-compose up --no-build -d');
    }
}