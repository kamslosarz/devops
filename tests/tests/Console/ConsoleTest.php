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
        $consoleParametersMock = m::mock(ConsoleParameters::class)
            ->shouldReceive('getCommand')
            ->once()
            ->andReturns('Admin\Create')
            ->getMock()
            ->shouldReceive('getParameters')
            ->once()
            ->andReturns([
                'TestExecuteCommandAdmin',
                'TestExecuteCommandAdminPassword'
            ])
            ->getMock();

        $results = (new Console($consoleParametersMock))->run();
        $this->assertEquals('Admin created', $results);
    }

    /**
     * @dataProvider shouldThrowConsoleException
     */
    public function testShouldThrowConsoleException($exceptionMessage, $command, $parameters)
    {
        $this->expectException(ConsoleException::class);
        $this->expectExceptionMessage($exceptionMessage);

        $consoleParametersMock = m::mock(ConsoleParameters::class)
            ->shouldReceive('getCommand')
            ->once()
            ->andReturns($command)
            ->getMock()
            ->shouldReceive('getParameters')
            ->once()
            ->andReturns($parameters)
            ->getMock();

        $console = new Console($consoleParametersMock);

        $console->run();
    }

    public function shouldThrowConsoleException()
    {
        return [
            'invalid command' => [
                'Command not found',
                'InvalidCommand\InvalidAction',
                []
            ],
            'Invalid Number of parameters' => [
                'Invalid number of parameters',
                'Admin\Create',
                []
            ],
        ];
    }

}