<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\Command\Docker\Stop;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;


class StopTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldStopDockerContainer()
    {
        $stop = m::mock(Stop::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial()
            ->shouldReceive('executeInShell')
            ->with('docker-compose down')
            ->once()
            ->getMock();
        $stop->execute(m::mock(CommandParameters::class));

        $stop->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $stop->shouldHaveReceived('executeInShell');
    }

    public function testShouldValidateCommand()
    {
        $stop = new Stop();
        $stop->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $this->assertThat($stop->isValid(m::mock(CommandParameters::class)), self::isTrue());
    }
}