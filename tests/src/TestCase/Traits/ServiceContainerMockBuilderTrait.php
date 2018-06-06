<?php

namespace Test\TestCase\Traits;

use Test\Decorator\ContainerMockBuilder;

trait ServiceContainerMockBuilderTrait
{
    /**
     * @return ContainerMockBuilder
     */
    protected function getServiceContainerMockBuilder()
    {
        return new ContainerMockBuilder();
    }
}