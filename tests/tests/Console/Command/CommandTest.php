<?php

namespace tests\Console\Command;

use Application\Console\Command\Command;
use Application\Console\Command\CommandParameters;
use Application\EventManager\Event;
use PHPUnit\Framework\TestCase;
use Test\Decorator\CommandDecorator;
use Mockery as m;

class CommandTest extends TestCase
{
    public function testShouldInvokeCommand()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->getMock();
        $command = new CommandDecorator($event);
        $response = $command->execute(m::mock(CommandParameters::class));

        $this->assertThat($response->getContent(), self::equalTo('Test command invoked'));
    }

    public function testShouldSetOutput()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->getMock();

        $command = new CommandDecorator($event);
        $response = $command->executeSetOutput(m::mock(CommandParameters::class));

        $this->assertThat($response->getContent(), self::equalTo('executeSetOutput command invoked'));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldExecuteInShell()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->getMock();

        $command = m::mock(CommandDecorator::class)
            ->makePartial()
            ->shouldAllowMockingProtectedMethods();

        $command->executeInShelDecorator('test command', ['parameter1', 'parameter2']);

        $command->shouldHaveReceived('executeInShell')
            ->with('test command', ['parameter1', 'parameter2'])
            ->once();
    }

}