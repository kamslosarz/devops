<?php

set_include_path(__DIR__);

const PROPEL_CONFIG = 'config/propel/config.php';
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
    $console = new Console(new ConsoleParameters($argv), ['servicesMapFile' => dirname(__DIR__) . '/config/serviceMap.php']);

    echo $console();
}
catch(\Application\Console\ConsoleException $e)
{
    echo sprintf('ERROR: %s in %s', $e->getMessage(), $e->getTraceAsString());
}
catch(\Exception $e)
{
    echo sprintf('ERROR: %s in %s', $e->getMessage(), $e->getTraceAsString());
}

echo PHP_EOL;
