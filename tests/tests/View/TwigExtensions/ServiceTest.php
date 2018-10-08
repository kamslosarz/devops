<?php

namespace tests\View\TwigExtensions;

use Application\View\Twig\TwigExtensions\Messages;
use Application\View\Twig\TwigExtensions\Service;
use Test\TestCase\TwigExtensionTestCase;
use Mockery as m;


class ServiceTest extends TwigExtensionTestCase
{
    function testShouldGetGlobals()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Service($serviceContainerMock);
        $globals = $extension->getGlobals();

        $this->assertInstanceOf(Service::class, $globals['services']);
    }

    function testShouldGetFilters()
    {
        $reflection = new \ReflectionClass($this);
        $this->assertFalse($reflection->hasMethod('getFilters'));
    }

    function testShouldGetFunctions()
    {
        $reflection = new \ReflectionClass($this);
        $this->assertFalse($reflection->hasMethod('getFunctions'));
    }

    public function testShouldGetService()
    {
        $service = new Service($this->getServiceContainerMockBuilder()->build());
        $this->assertNotNull($service->getService('request'));
    }
}