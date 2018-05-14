<?php

include "../vendor/autoload.php";
require_once '../data/generated-conf/config.php';

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

echo PHP_EOL;
