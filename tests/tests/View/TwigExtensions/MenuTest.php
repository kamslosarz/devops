<?php

namespace tests\View\TwigExtensions;

use Application\View\Twig\TwigExtensions\Menu;
use Test\TestCase\TwigExtensionTestCase;


class MenuTest extends TwigExtensionTestCase
{
    public function testShouldGetGlobals()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Menu($serviceContainerMock);
        $globals = $extension->getGlobals();

        $this->assertInstanceOf(Menu::class, $globals['menu']);
    }

    public function testShouldGetFilters()
    {
        $reflection = new \ReflectionClass($this);
        $this->assertFalse($reflection->hasMethod('getFilters'));
    }

    public function testShouldGetFunctions()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Menu($serviceContainerMock);
        $functions = $extension->getFunctions();

        $this->assertEquals('url', $functions[0]->getName());
        $this->assertEquals('isUri', $functions[1]->getName());
    }

    public function testShouldCreateUrl()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Menu($serviceContainerMock);
        $url = $extension->url('/test/test.html', 'extra-icon', 'some link item title');
        $this->assertEquals("<a href='/test/test.html'><i class='extra-icon'></i><p>some link item title</p></a>", $url);
    }

    public function testShouldCheckIfUriIsActivePage()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()
            ->setRequestMock(
                $this->getServiceContainerMockBuilder()
                    ->getRequestMock()
                    ->shouldReceive('getRequestUri')
                    ->andReturn('/test/test/test.html')
                    ->getMock()
            )
            ->build();

        $extension = new Menu($serviceContainerMock);
        $this->assertTrue($extension->isUri('/admin/index'));
        $this->assertTrue($extension->isUri('/admin/*'));

    }
}