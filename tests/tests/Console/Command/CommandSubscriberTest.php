<?php

namespace tests\Console\Command;


use Application\Console\Command\CommandSubscriber;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

class CommandSubscriberTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldConstructCommandSubscriber()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setCommandRouterMock(
            $serviceContainerMockBuilder->getCommandRouterMock()
                ->shouldReceive('getRoutes')
                ->andReturn(include FIXTURE_DIR . '/config/commands.php')
                ->getMock()
        );

        $commandSubscriber = new CommandSubscriber($serviceContainerMockBuilder->build());

        $this->assertThat($commandSubscriber, self::isInstanceOf(CommandSubscriber::class));
    }
}