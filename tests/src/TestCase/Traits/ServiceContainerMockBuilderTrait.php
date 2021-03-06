<?php

namespace Test\TestCase\Traits;

use Test\MockBuilder\ContainerMockBuilder;
use Test\MockBuilder\ServiceContainerMockBuilder;

trait ServiceContainerMockBuilderTrait
{
    /**
     * @return ServiceContainerMockBuilder
     */
    protected function getServiceContainerMockBuilder()
    {
        return new ServiceContainerMockBuilder();
    }

    protected function getServiceContainerConfig()
    {
        return include FIXTURE_DIR . '/config/servicesMap.php';
    }
}