<?php

namespace Application\Console\Command;

use Application\Config\Config;

class Cache extends Command

    public function isValid($dockerName = null)
    {
        return true;
    }
    
    public function clear($dockerName = null)
    {
        shell_exec(sprintf('sudo rm -rf %s/assets/*', Config::get('web_dir')));
    }
}