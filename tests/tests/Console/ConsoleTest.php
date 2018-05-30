<?php

namespace src\Console\Command;

use Application\Console\Console;
use Application\Console\ConsoleParameters;
use Test\TestCase\ConsoleTestCase;

class ConsoleTest extends ConsoleTestCase
{
    public function testInstance(){

        $consoleParameters = new ConsoleParameters([
            'test',
            'test'
        ]);

        $console = new Console($consoleParameters);
        $this->assertInstanceOf(Console::class, $console);
    }
}