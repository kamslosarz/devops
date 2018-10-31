<?php

use Application\Console\Console;
use Application\Console\ConsoleException;
use Application\Console\ConsoleParameters;
use Mockery as m;
use Test\TestCase\ConsoleTestCase;

class ConsoleTest extends ConsoleTestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldInvokeConsoleCommand()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $consoleParameters = m::mock(ConsoleParameters::class);
        $consoleParameters
            ->shouldReceive('getCommand')
            ->andReturn('test:command')
            ->getMock()
            ->shouldReceive('getCommandParameters')
            ->andReturn(
                m::mock(\Application\Console\Command\CommandParameters::class)
                    ->shouldReceive('toArray')
                    ->andReturns([])
                    ->getMock()
            )
            ->getMock();

        $console = new Console($consoleParameters, $this->getServiceContainerConfig());
        $response = $console();

        $this->assertThat($response->getContent(), self::equalTo('Test command invoked'));
    }
}