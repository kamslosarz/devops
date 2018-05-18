<?php

set_include_path(dirname(__DIR__));

if(!file_exists('vendor/autoload.php') || !file_exists('data/generated-conf/config.php'))
{
    echo 'ERROR: Run this file from project directory';
}

include "vendor/autoload.php";
include 'data/generated-conf/config.php';

use Application\Console\Console;
use Application\Console\ConsoleParameters;

try
{
    $console = new Console(new ConsoleParameters($argv));
    echo $console->run();
}
catch(\Application\Console\ConsoleException $consoleException)
{
    echo 'ERROR: ' . $consoleException->getMessage();
}
catch(\Exception $e)
{
    echo 'ERROR: ' . $e->getMessage();
}

echo PHP_EOL;
