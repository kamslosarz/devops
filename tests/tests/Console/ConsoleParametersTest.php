<?php

namespace tests\Console;

use Application\Console\ConsoleParameters;
use PHPUnit\Framework\TestCase;

class ConsoleParametersTest extends TestCase
{
    public function testShouldConstructParameters()
    {
        $consoleParameters = new ConsoleParameters([
            'parameter1',
            'parameter2'
        ]);

        $this->assertEquals('parameter2', $consoleParameters->getCommand());
    }

    public function testShouldGetCommandParameters()
    {
        $consoleParameters = new ConsoleParameters([
            'parameter1',
            'parameter2',
            'parameter3',
        ]);

        $commandParameters = $consoleParameters->getCommandParameters();
        $this->assertEquals(['parameter3'], $commandParameters->toArray());
    }

    /**
     * @dataProvider commandsDataProvider
     * @param $command
     * @param $commandNamespace
     */
    public function testShouldGetCommandName($command, $commandNamespace)
    {
        $consoleParameters = new ConsoleParameters([
            '',
            $command,
        ]);

        $this->assertEquals($commandNamespace, $consoleParameters->getCommand());
    }

    public function commandsDataProvider()
    {
        return [
            'Test case 1' => [
                'test:test',
                'Test\Test'
            ],
            'Test case 2' => [
                'test:command:to:do:smth',
                'Test\Command\To\Do\Smth'
            ],
            'Test case 3' => [
                'test:Upper-and-Lower:Cases',
                'Test\UpperAndLower\Cases'
            ]
        ];
    }
}