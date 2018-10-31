<?php

namespace Application\Console\Command\Command\Cache;

use Application\Console\Command\Command;
use Application\Console\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Clear extends Command
{
    public function execute(): ConsoleResponse
    {
        $config = $this->event->getServiceContainer()->getService('config');

        return $this->addOutput(shell_exec(sprintf('rm -rf %s/assets/*', $config->web_dir)))
            ->addOutput('Cache cleared')
            ->sendOutput();
    }
}