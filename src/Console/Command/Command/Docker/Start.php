<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\ConsoleExecutable;

class Start extends ConsoleExecutable
{
    public function execute()
    {
        $this->executeInShell('docker-compose up --no-build -d');
    }
}