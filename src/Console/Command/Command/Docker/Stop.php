<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\ConsoleExecutable;

class Stop extends ConsoleExecutable
{
    public function execute()
    {
        $this->executeInShell('docker-compose down');
    }

    public function isValid()
    {
        return true;
    }
}