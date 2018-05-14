<?php

use Application\Service\ServiceContainer\ServiceContainer;

class ServiceContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldCreateInstance()
    {
        $serviceContainer = new ServiceContainer();

        $this->assertInstanceOf(ServiceContainer::class, $serviceContainer);
    }
}