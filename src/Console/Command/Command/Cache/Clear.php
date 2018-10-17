<?php

namespace Application\Console\Command\Command\Cache;

use Application\Config\Config;
use Application\Console\Command\Command;
use Application\Console\Command\Command\CommandParameters;

class Clear extends Command
{
    public function execute(CommandParameters $commandParameters)
    {
        shell_exec(sprintf('rm -rf %s/assets/*', Config::get('web_dir')));
    }

    public function isValid(CommandParameters $commandParameters)
    {
        return true;
    }
}