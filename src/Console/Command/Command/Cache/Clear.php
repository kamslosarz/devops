<?php

namespace Application\Console\Command\Command\Cache;

use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;
use Application\Response\ResponseTypes\ConsoleResponse;

class Clear extends Command
{
    public function execute(CommandParameters $commandParameters): ConsoleResponse
    {
        $this->addOutput(shell_exec(sprintf('rm -rf %s/assets/*', Config::get('web_dir'))))->sendOutput();
    }
}