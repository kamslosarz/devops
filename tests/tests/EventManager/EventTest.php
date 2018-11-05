<?php

namespace tests\EventManager;

use Application\EventManager\Event;
use Application\ParameterHolder\ParameterHolder;
use Application\Response\Response;
use Application\Service\ServiceContainer\ServiceContainer;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class EventTest extends TestCase
{

    public function testShouldConstructEvent()
    {
        $event = new Event();

        $this->assertThat($event, self::isInstanceOf(Event::class));
    }

    public function testShouldSetServiceContainer()
    {
        $event = new Event();
        $serviceContainerMock = m::mock(ServiceContainer::class);
        $event->setServiceContainer($serviceContainerMock);
        $reflectionClass = new \ReflectionClass($event);
        $property = $reflectionClass->getProperty('serviceContainer');
        $property->setAccessible(true);
        $this->assertThat($property->getValue($event), self::equalTo($serviceContainerMock));
    }

    public function testShouldSetResponse()
    {
        $event = new Event();
        $responseMock = m::mock(Response::class);
        $event->setResponse($responseMock);
        $this->assertThat($event->getResponse(), self::equalTo($responseMock));
    }

    public function testShouldSetParameters()
    {
        $event = new Event();
        $parameterHolderMock = m::mock(ParameterHolder::class);
        $event->setParameters($parameterHolderMock);

        $reflectionClass = new \ReflectionClass($event);
        $property = $reflectionClass->getProperty('parameters');
        $property->setAccessible(true);

        $this->assertThat($property->getValue($event), self::equalTo($parameterHolderMock));
    }
}