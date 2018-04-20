<?php

set_include_path(dirname(__DIR__));
include "vendor/autoload.php";

use Application\Console\Console;
use Application\Console\ConsoleParameters;

try {

    $console = new Console(new ConsoleParameters($argv));

    echo $console->run();
} catch (\Application\Console\ConsoleException $consoleException) {

    echo $consoleException->getMessage();
} catch (\Exception $e) {

    echo $e->getMessage();
}
