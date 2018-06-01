<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\ConsoleExecutable;

class Start extends ConsoleExecutable
{
    public function execute()
    {
        $this->executeInShell('docker-compose up --build -d');
    }

    public function isValid()
    {
        return true;
    }
}