<?php

use Application\Console\Console;
use Application\Console\ConsoleException;
use Application\Console\ConsoleParameters;
use Mockery as m;
use Test\TestCase\ConsoleTestCase;

class ConsoleTest extends ConsoleTestCase
{
    public function testShouldCreateInstance()
    {
        $consoleParameters = new ConsoleParameters([
            'test',
            'test'
        ]);

        $console = new Console($consoleParameters);
        $this->assertInstanceOf(Console::class, $console);
    }

    /**
     * @throws ConsoleException
     * @throws ReflectionException
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    public function testShouldExecuteCommand()
    {
        $commandParametersMock = m::mock(\Application\Console\Command\Command\CommandParameters::class)
            ->shouldReceive('toArray')
            ->andReturns([
                'TestExecuteCommandAdminUsername',
                'TestExecuteCommandAdminPassword'
            ])
            ->getMock();

        $consoleParametersMock = m::mock(ConsoleParameters::class)
            ->shouldReceive('getCommand')
            ->once()
            ->andReturns('Admin\Create')
            ->getMock()
            ->shouldReceive('getCommandParameters')
            ->once()
            ->andReturns($commandParametersMock)
            ->getMock();

        $results = (new Console($consoleParametersMock))();
        $this->assertEquals('Admin created', $results);
    }

    /**
     * @param $exceptionMessage
     * @param $command
     * @param $parameters
     * @throws ConsoleException
     * @throws ReflectionException
     * @throws \Application\Router\Dispatcher\DispatcherException
     *
     * @dataProvider shouldThrowConsoleException
     */
    public function testShouldThrowConsoleException($exceptionMessage, $command, $parameters)
    {
        $commandParametersMock = m::mock(\Application\Console\Command\Command\CommandParameters::class);
        foreach($parameters as $key => $value)
        {
            $commandParametersMock->{$key} = $value;
        }

        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $consoleParametersMock = m::mock(ConsoleParameters::class)
            ->shouldReceive('getCommand')
            ->once()
            ->andReturns($command)
            ->getMock()
            ->shouldReceive('getCommandParameters')
            ->once()
            ->andReturns($parameters)
            ->getMock();

        $console = new Console($consoleParametersMock);

        $console();
    }

    public function shouldThrowConsoleException()
    {
        return [
            'invalid command' => [
                'Command InvalidCommand\InvalidAction not found',
                'InvalidCommand\InvalidAction',
                []
            ]
        ];
    }

}