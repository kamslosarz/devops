<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\Docker\Build;
use Application\Console\Command\Command\Docker\Start;
use Application\EventManager\Event;
use Application\ParameterHolder\ParameterHolder;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class StartTest extends TestCase
{
    public function testShouldConstructInstance()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Start($event);

        $this->assertThat($build, self::isInstanceOf(Start::class));
    }

    public function testShouldExecuteCommand()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = m::mock(Start::class, [$event])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('executeInShell')
            ->with('docker-compose up --no-build -d')
            ->andReturn('method called')
            ->getMock();

        $results = $build->execute();

        $build->shouldHaveReceived('executeInShell')->once();
        $this->assertThat($results->getContent(), self::equalTo('method called'));
    }
}