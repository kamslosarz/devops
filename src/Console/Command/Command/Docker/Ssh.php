<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command;
use Application\Console\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Ssh extends Command
{
    const DOCKER_CONTAINER_NAME = 'devops_www_1';
    const DOCKER_USERNAME = 'devops';

    /**
     * @param string $username
     * @return ConsoleResponse
     */
    public function execute($username = self::DOCKER_USERNAME): ConsoleResponse
    {
        $this->executeInShell('docker exec -it -u %s %s /bin/bash', [
            $username, self::DOCKER_CONTAINER_NAME
        ]);

        return $this->sendOutput();
    }
}