<?php

namespace Test\TestCase\Traits;

use Test\MockBuilder\ContainerMockBuilder;

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