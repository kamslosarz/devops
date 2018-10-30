<?php

use Application\Service\ServiceContainer\ServiceContainer;

class ServiceContainerTest extends \PHPUnit\Framework\TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    public function testShouldCreateInstance()
    {
        $serviceContainer = new ServiceContainer($this->getServiceContainerConfig());

        $this->assertInstanceOf(ServiceContainer::class, $serviceContainer);
    }
}