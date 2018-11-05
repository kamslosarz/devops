<?php

set_include_path(__DIR__);

const PROPEL_CONFIG = 'config/propel_config.php';
const AUTOLOAD_FILE = 'vendor/autoload.php';

if(!file_exists(AUTOLOAD_FILE) || !file_exists(PROPEL_CONFIG))
{
    echo 'ERROR: Run this file from project directory';
    exit(PHP_EOL);
}

require AUTOLOAD_FILE;
require PROPEL_CONFIG;

use Application\Console\Console;
use Application\Console\ConsoleParameters;

try
{
    (new Console(new ConsoleParameters($argv), (include dirname(__DIR__) . '/config/config.php')['servicesMap']))()();

    echo PHP_EOL;
}
catch(\Exception $e)
{
    echo sprintf('ERROR: %s' . PHP_EOL, $e->getMessage());
}
