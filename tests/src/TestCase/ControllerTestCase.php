<?php

namespace Test\TestCase;

use Application\Container\Appender\Appender;
use Application\Container\Container;
use Application\Service\Session\Session;
use \Mockery as m;
use PHPUnit\Framework\TestCase;

abstract class ControllerTestCase extends TestCase
{
    /**
     * @return m\MockInterface
     */
    public function getContainerMock()
    {
        $sessionMock = m::mock(\Application\Service\Session\Session::class);
        $sessionMock->shouldReceive('save')
            ->once()
            ->getMock();

        $authServiceMock = m::mock(\Application\Service\AuthService\AuthService::class);
        $authServiceMock->shouldReceive('hasAccess')
            ->once()
            ->andReturns(false)
            ->getMock()
            ->shouldReceive('getSession')
            ->andReturns($sessionMock)
            ->getMock();

        $serviceContainerMock = m::mock(\Application\Service\ServiceContainer\ServiceContainer::class);
        $serviceContainerMock->shouldReceive('getService')
            ->andReturns($authServiceMock)
            ->once()
            ->getMock();

        $responseMock = m::mock(\Application\Response\Response::class);
        $responseMock->shouldReceive('setHeaders')
            ->once()
            ->getMock()->shouldReceive('__invoke')
            ->once()
            ->getMock();

        $routerMock = m::mock(\Application\Router\Router::class);
        $routerMock->shouldReceive('getRouteByParameters')
            ->once()
            ->andReturns('/admin/index')
            ->getMock();

        $contextMock = m::mock(\Application\Context\Context::class);
        $contextMock->shouldReceive('getRouter')
            ->once()
            ->andReturns($routerMock)
            ->getMock();

        return m::mock(Container::class)
            ->shouldReceive('getSession')
            ->andReturn(m::mock(Session::class))
            ->getMock()
            ->shouldReceive('getServiceContainer')
            ->andReturns($serviceContainerMock)
            ->once()
            ->getMock()
            ->shouldReceive('getResponse')
            ->once()
            ->andReturns($responseMock)
            ->getMock()
            ->shouldReceive('getContext')
            ->once()
            ->andReturns($contextMock)
            ->getMock();
    }

    /**
     * @return m\MockInterface
     */
    public function getAppenderMock()
    {
        return m::mock(Appender::class);
    }

}