<?php

class ServiceContainerTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldCreateInstance()
    {
        $serviceContainer = new \Application\ServiceContainer\ServiceContainer();

        $this->assertInstanceOf(\Application\ServiceContainer\ServiceContainer::class, $serviceContainer);
    }
}