<?php

namespace Application\Console\Command\Command;

use Application\Config\Config;
use Application\Console\Command\Command;

class Cache extends Command
{
    public function clear($dockerName = null)
    {
        shell_exec(sprintf('sudo rm -rf %s/assets/*', Config::get('web_dir')));
    }

    public function isValid()
    {
        return true;
    }
}