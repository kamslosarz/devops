<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\Docker\Build;
use Application\EventManager\Event;
use Application\ParameterHolder\ParameterHolder;
use PHPUnit\Framework\TestCase;
use Mockery as m;


class BuildTest extends TestCase
{
    public function testShouldConstructInstance()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Build($event);

        $this->assertThat($build, self::isInstanceOf(Build::class));
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

        $build = m::mock(Build::class, [$event])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('executeInShell')
            ->with('docker-compose up --build -d')
            ->andReturn('method called')
            ->getMock();

        $results = $build->execute();

        $build->shouldHaveReceived('executeInShell')->once();
        $this->assertThat($results->getContent(), self::equalTo('method called'));
    }
}