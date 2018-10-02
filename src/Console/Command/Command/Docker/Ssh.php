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

        $this->executeInShell('docker exec -it -u devops %s /bin/bash', [$dockerName]);
    }

    public function isValid(CommandParameters $commandParameters)
    {
        return true;
    }
}