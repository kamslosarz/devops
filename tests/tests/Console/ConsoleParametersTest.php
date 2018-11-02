<?php

namespace tests\Console;


use Application\Console\ConsoleParameters;
use PHPUnit\Framework\TestCase;

class ConsoleParametersTest extends TestCase
{
    public function testShouldConstructConsoleParameters()
    {
        $consoleParameters = new ConsoleParameters([
            'admin:create'
        ]);

        $this->assertThat($consoleParameters, self::isInstanceOf(ConsoleParameters::class));
    }

    public function testShouldGetCommand()
    {
        $consoleParameters = new ConsoleParameters([
            'test', 'test:command', 'args args'
        ]);

        $this->assertThat($consoleParameters->getCommand(), self::equalTo('test:command'));
    }

    public function testShouldGetCommandParameters()
    {
        $consoleParameters = new ConsoleParameters([
            'test', 'test:command', 'args args', 'args2', 'arg3'
        ]);

        $this->assertThat($consoleParameters->getCommandParameters()->toArray(), self::equalTo([
            'args args', 'args2', 'arg3'
        ]));
    }
}