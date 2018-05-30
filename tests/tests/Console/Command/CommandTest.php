<?php

namespace src\Console\Command;

use Application\Console\Command\Command;
use Application\Console\Command\CommandException;
use Test\TestCase\ConsoleTestCase;

class CommandTest extends ConsoleTestCase
{
    /**
     * @dataProvider shouldReturnInstanceDataProvider
     * @param $command
     * @throws CommandException
     */
    public function testShouldReturnInstance($class, $commandName)
    {
        $command = Command::getInstance($commandName);

        $this->assertInstanceOf($class, $command);
    }

    public function shouldReturnInstanceDataProvider()
    {
        return [
            'DataSet Admin command' => [
                Command\Admin::class,
                'Admin'
            ],
            'DataSet Docker command' => [
                Command\Docker::class,
                'Docker'
            ],
            'DataSet Cache command' => [
                Command\Cache::class,
                'Cache'
            ]
        ];
    }

    /**
     * @throws CommandException
     */
    public function testShouldReturnCommandException()
    {
        $this->expectException(CommandException::class);
        $this->expectExceptionMessage('Command \'notExistingCommand\' not found');

        Command::getInstance('notExistingCommand');
    }
}