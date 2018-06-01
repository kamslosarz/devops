<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\ConsoleExecutable;

class Ssh extends ConsoleExecutable
{
    const DOCKER_CONTAINER_NAME = 'devops_www_1';

    public function execute($dockerName = null)
    {
        $dockerName = $dockerName ? $dockerName : self::DOCKER_CONTAINER_NAME;

        $this->executeInShell('docker exec -it -u devops %s /bin/bash', [$dockerName]);
    }

    public function isValid($dockerName = null)
    {
        return true;
    }
}