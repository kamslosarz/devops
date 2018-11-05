<?php

namespace tests\EventManager;


use Application\EventManager\EventSubscriber;
use Application\Service\ServiceContainer\ServiceContainer;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class EventSubscriberTest extends TestCase
{
    public function testShouldConstructEventSubscriver()
    {
        $serviceContainerMock = m::mock(ServiceContainer::class);
        $eventSubscriber = m::mock(EventSubscriber::class, [$serviceContainerMock]);

        $this->assertThat($eventSubscriber, self::isInstanceOf(EventSubscriber::class));
    }

    public function testShouldGetSubscribedEvents()
    {
        $serviceContainerMock = m::mock(ServiceContainer::class);
        $eventSubscriber = m::mock(EventSubscriber::class, [$serviceContainerMock])->makePartial();

        $reflectionClass = new \ReflectionClass($eventSubscriber);
        $property = $reflectionClass->getProperty('subscribedEvents');
        $property->setAccessible(true);
        $property->setValue($eventSubscriber, ['subscribedEvents']);

        $this->assertThat($eventSubscriber->getSubscribedEvents(), self::equalTo(['subscribedEvents']));
    }
}