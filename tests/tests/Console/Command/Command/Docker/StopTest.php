<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\Docker\Stop;
use Application\EventManager\Event;
use Application\ParameterHolder\ParameterHolder;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class StopTest extends TestCase
{
    public function testShouldConstructInstance()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $Stop = new Stop($event);

        $this->assertThat($Stop, self::isInstanceOf(Stop::class));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldExecuteCommand()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $Stop = m::mock(Stop::class, [$event])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('executeInShell')
            ->with('docker-compose down')
            ->andReturn('method called')
            ->getMock();

        $results = $Stop->execute();

        $Stop->shouldHaveReceived('executeInShell')->once();
        $this->assertThat($results->getContent(), self::equalTo('method called'));
    }
}