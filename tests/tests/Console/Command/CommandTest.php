<?php

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
        $command = Command::getInstance((new \Application\Console\ConsoleParameters(['',
            $commandName
        ]))->getCommand());

        $this->assertInstanceOf($class, $command);
    }

    public function shouldReturnInstanceDataProvider()
    {
        return [
            'DataSet Admin command' => [
                Command\Admin\Create::class,
                'admin:create'
            ],
            'DataSet Clear cache command' => [
                Command\Cache\Clear::class,
                'cache:clear'
            ],
            'DataSet ssh command' => [
                Command\Docker\Ssh::class,
                'docker:ssh'
            ],
            'DataSet start command' => [
                Command\Docker\Start::class,
                'docker:start'
            ],
            'DataSet stop command' => [
                Command\Docker\Stop::class,
                'docker:stop'
            ]
        ];
    }

    /**
     * @throws CommandException
     */
    public function testShouldReturnNull()
    {
        $command = Command::getInstance((new \Application\Console\ConsoleParameters(['',
            'notExistingCommand'
        ]))->getCommand());

        $this->assertNull($command);
    }
}