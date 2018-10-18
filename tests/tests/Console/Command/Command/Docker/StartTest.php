<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\Command\Docker\Start;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;


class StartTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldStartDockerContainer()
    {
        $start = m::mock(Start::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial()
            ->shouldReceive('executeInShell')
            ->with('docker-compose up --no-build -d')
            ->once()
            ->getMock();
        $start->execute(m::mock(CommandParameters::class));

        $start->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $start->shouldHaveReceived('executeInShell');
    }

    public function testShouldValidateCommand()
    {
        $start = new Start();
        $start->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $this->assertThat($start->isValid(m::mock(CommandParameters::class)), self::isTrue());
    }
}