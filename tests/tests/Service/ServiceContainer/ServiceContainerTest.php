<?php

namespace tests\Service\ServiceContainer;


use Application\Service\ServiceContainer\ServiceContainer;
use PHPUnit\Framework\TestCase;
use Test\Decorator\ServiceDecorator;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

class ServiceContainerTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldLoadServiceWithDependencies()
    {
        $serviceContainer = new ServiceContainer($this->getServiceContainerConfig());
        $testService = $serviceContainer->getService('testService');
        $this->assertInstanceOf(ServiceDecorator::class, $testService);

    }
}