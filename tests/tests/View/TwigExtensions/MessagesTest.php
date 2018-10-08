<?php

namespace tests\View\TwigExtensions;

use Application\View\Twig\TwigExtensions\Messages;
use Test\TestCase\TwigExtensionTestCase;
use Mockery as m;


class MessagesTest extends TwigExtensionTestCase
{
    function testShouldGetGlobals()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Messages($serviceContainerMock);
        $globals = $extension->getGlobals();

        $this->assertInstanceOf(Messages::class, $globals['messages']);
    }

    function testShouldGetFilters()
    {
        $reflection = new \ReflectionClass($this);
        $this->assertFalse($reflection->hasMethod('getFilters'));
    }

    function testShouldGetFunctions()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $extension = new Messages($serviceContainerMock);
        $functions = $extension->getFunctions();

        /** @var \Twig_Function $function */
        $function = $functions[0];

        $this->assertEquals('messages', $function->getName());
    }

    public function testShouldGetAllMessages()
    {
        $sessionMock = m::mock(Session::class)
            ->shouldReceive('get')
            ->with('messages')
            ->andReturn([
                'INFO' => 'test messsage',
                'SUCCESS' => 'test messsage success',
            ])
            ->getMock()
            ->shouldReceive('set')
            ->with('messages', null)
            ->andReturnSelf()
            ->getMock();

        $messages = new Messages($this->getServiceContainerMockBuilder()
            ->setSessionMock($sessionMock)->build());

        $this->assertEquals([
            'INFO' => 'test messsage',
            'SUCCESS' => 'test messsage success',
        ], $messages->getAll());

    }
}