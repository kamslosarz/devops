<?php

namespace tests\Context;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Context\Context;
use Application\Router\Router;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\Service\Session\Session;
use Mockery as m;
use PHPUnit\Framework\TestCase;


class ContextTest extends TestCase
{
    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldConstructContext()
    {
        $context = new Context($this->getContainerMock());

        $this->assertInstanceOf(Context::class, $context);
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnNulledRouter()
    {
        $context = new Context($this->getContainerMock());

        $this->assertEquals(null, $context->getRouter());
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldReturnAppender()
    {
        $context = new Context($this->getContainerMock());

        $this->assertInstanceOf(Appender::class, $context->getAppender());
    }

    private function getContainerMock()
    {
        $sessionMock = m::mock(Session::class);

        $serviceContainerMock = m::mock(ServiceContainer::class)
            ->shouldReceive('getService')
            ->once()
            ->withArgs(['session'])
            ->andReturns($sessionMock)
            ->getMock();

        $containerMock = m::mock(Container::class)
            ->shouldReceive('getLogger')
            ->once()
            ->getMock()
            ->shouldReceive('getServiceContainer')
            ->once()
            ->andReturns($serviceContainerMock)
            ->getMock();

        return $containerMock;
    }
}