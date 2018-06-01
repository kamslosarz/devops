<?php

namespace Application\Console\Command\Command\Cache;

use Application\Config\Config;
use Application\Console\Command\Command;

class Clear extends Command
{
    public function execute($dockerName = null)
    {
        shell_exec(sprintf('sudo rm -rf %s/assets/*', Config::get('web_dir')));
    }

    public function isValid()
    {
        return true;
    }
}