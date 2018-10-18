<?php

namespace tests\Console\Command\Command\Docker;

use Application\Console\Command\Command\CommandParameters;
use Application\Console\Command\Command\Docker\Build;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;


class BuildTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldValidateCommand()
    {
        $build = m::mock(Build::class)
            ->shouldAllowMockingProtectedMethods()
            ->makePartial()
            ->shouldReceive('executeInShell')
            ->with('docker-compose up --build -d')
            ->once()
            ->andReturnUsing(function ($arg){
                return $arg;
            })->getMock();

        $build->setServiceContainer($this->getServiceContainerMockBuilder()->build());
        $build->execute(m::mock(CommandParameters::class));
        $build->shouldHaveReceived('executeInShell')->once();
    }
}