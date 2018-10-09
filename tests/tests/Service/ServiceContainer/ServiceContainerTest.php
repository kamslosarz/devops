<?php

namespace tests\Service\ServiceContainer;


use Application\Service\ServiceContainer\ServiceContainer;
use PHPUnit\Framework\TestCase;
use Test\Decorator\ServiceDecorator;

class ServiceContainerTest extends TestCase
{
    public function testShouldLoadServiceWithDependencies()
    {
        $serviceContainer = new ServiceContainer();
        $testService = $serviceContainer->getService('testService');
        $this->assertInstanceOf(ServiceDecorator::class, $testService);


    }
}