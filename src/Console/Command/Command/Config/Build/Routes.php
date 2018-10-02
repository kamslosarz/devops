<?php

namespace Application\Console\Command\Command\Config\Build;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\ConsoleExecutable;

class Routes extends ConsoleExecutable
{
    public function execute(CommandParameters $commandParameters)
    {
        $this->addOutput('dupa');


        return $this->sendOutput();
    }
}