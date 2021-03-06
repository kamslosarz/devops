<?php

namespace tests\View\TwigExtensions;

use Application\Config\Config;
use Application\View\Twig\TwigExtensions\Asset;
use Test\TestCase\TwigExtensionTestCase;


class AssetTest extends TwigExtensionTestCase
{
    function testShouldGetGlobals()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Asset($serviceContainerMock);
        $globals = $extension->getGlobals();

        $this->assertInstanceOf(Asset::class, $globals['asset']);
    }

    function testShouldGetFilters()
    {
        $reflection = new \ReflectionClass($this);
        $this->assertFalse($reflection->hasMethod('getFilters'));
    }

    function testShouldGetFunctions()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Asset($serviceContainerMock);
        $functions = $extension->getFunctions();

        /** @var \Twig_Function $function */
        $function = $functions[0];

        $this->assertEquals('asset', $function->getName());
    }
}