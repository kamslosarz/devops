<?php

namespace Application\Console\Command\Command\Docker;

use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Ssh extends Command
{
    const DOCKER_CONTAINER_NAME = 'devops_www_1';

    /**
     * @param CommandParameters $commandParameters
     * @return ConsoleResponse
     */
    public function execute(CommandParameters $commandParameters): ConsoleResponse
    {
        $dockerName = $commandParameters->dockerName ? $commandParameters->dockerName : self::DOCKER_CONTAINER_NAME;

        if($commandParameters->offsetExists(0))
        {
            $this->executeInShell('docker exec -it -u %s %s /bin/bash', [
                $commandParameters->offsetGet(0), $dockerName
            ]);
        }
        else
        {
            $this->executeInShell('docker exec -it -u devops %s /bin/bash', [$dockerName]);
        }

        return $this->sendOutput();
    }
}