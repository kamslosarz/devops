<?php

namespace tests\Console;

use Application\Console\CommandSubscriber;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

class CommandSubscriberTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \ReflectionException
     */
    public function test()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setCommandRouterMock(
            $serviceContainerMockBuilder->getCommandRouterMock()
                ->shouldReceive('getRoutes')
                ->andReturn(['admin:create' => [\Application\Console\Command\Command\Admin\Create::class, 'execute']])
                ->getMock()
        );

        $commandSubscriber = new CommandSubscriber($serviceContainerMockBuilder->build());
        $reflectionClass = new \ReflectionClass($commandSubscriber);
        $subscribedEvents = $reflectionClass->getProperty('subscribedEvents');
        $subscribedEvents->setAccessible(true);

        $this->assertThat($subscribedEvents->getValue($commandSubscriber), self::equalTo([
                'admin:create' => [
                    \Application\Console\Command\Command\Admin\Create::class, 'execute'
                ]
            ]
        ));
    }
}