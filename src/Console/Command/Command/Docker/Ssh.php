<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\ConsoleExecutable;

class Ssh extends ConsoleExecutable
{
    const DOCKER_CONTAINER_NAME = 'devops_www_1';

    public function execute(CommandParameters $commandParameters)
    {
        $dockerName = $commandParameters->dockerName ? $commandParameters->dockerName : self::DOCKER_CONTAINER_NAME;

        if($commandParameters->offsetExists(0))
        {
            $this->executeInShell('docker exec -it -u %s %s /bin/bash', [$commandParameters->offsetGet(0), $dockerName]);
        }
        else
        {
            $this->executeInShell('docker exec -it -u devops %s /bin/bash', [$dockerName]);
        }
    }

    public function isValid(CommandParameters $commandParameters)
    {
        return true;
    }
}