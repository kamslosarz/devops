<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\Docker\Build;
use Application\Console\Command\Command\Docker\Ssh;
use Application\EventManager\Event;
use Application\ParameterHolder\ParameterHolder;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class SshTest extends TestCase
{
    public function testShouldConstructInstance()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $build = new Ssh($event);

        $this->assertThat($build, self::isInstanceOf(Ssh::class));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldExecuteCommandWithDefaultUser()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class))
            ->getMock();

        $ssh = m::mock(Ssh::class, [$event])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('executeInShell')
            ->getMock();

        $results = $ssh->execute();

        $ssh->shouldHaveReceived('executeInShell')
            ->withArgs([
                'docker exec -it -u %s %s /bin/bash', [
                    'devops',
                    'devops_www_1'
                ]
            ])
            ->once();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldExecuteCommandWithRoot()
    {
        $event = m::mock(Event::class)
            ->shouldReceive('getParameters')
            ->andReturn(m::mock(ParameterHolder::class, [
                ['username' => 'root']
            ]))
            ->getMock();

        $ssh = m::mock(Ssh::class, [$event])
            ->makePartial()
            ->shouldAllowMockingProtectedMethods()
            ->shouldReceive('executeInShell')
            ->getMock();

        $results = $ssh->execute('root');

        $ssh->shouldHaveReceived('executeInShell')
            ->withArgs([
                'docker exec -it -u %s %s /bin/bash', [
                    'root',
                    'devops_www_1'
                ]
            ])
            ->once();
    }
}