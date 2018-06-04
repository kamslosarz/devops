<?php

namespace Test\TestCase\Traits;

use Application\Router\Route;
use Mockery as m;

trait ServiceContainerMockTrait
{
    /**
     * @return m\MockInterface
     */
    public function getServiceContainerMock()
    {
        $sessionMock = m::mock(\Application\Service\Session\Session::class);
        $sessionMock->shouldReceive('save')
            ->once()
            ->getMock();

        $routeMock = m::mock(Route::class);
        $routeMock->shouldReceive('getAccess')
            ->andReturns(Route::ACCESS_PUBLIC)
            ->getMock();

        $requestMock = m::mock(Route::class)
            ->shouldReceive('getRoute')
            ->once()
            ->andReturns($routeMock)
            ->getMock()
            ->shouldReceive('getRequestUri')
            ->once()
            ->getMock();

        $authServiceMock = m::mock(\Application\Service\AuthService\AuthService::class);
        $authServiceMock->shouldReceive('hasAccess')
            ->once()
            ->andReturns(false)
            ->getMock()
            ->shouldReceive('getSession')
            ->andReturns($sessionMock)
            ->getMock()
            ->shouldReceive('isAuthenticated')
            ->once()
            ->andReturns(true)
            ->shouldReceive('getRoute')
            ->once()
            ->andReturns($routeMock)
            ->getMock();

        return m::mock(\Application\Service\ServiceContainer\ServiceContainer::class)
            ->shouldReceive('getService')
            ->once()
            ->withArgs(['session'])
            ->andReturns($sessionMock)
            ->getMock()
            ->shouldReceive('getService')
            ->once()
            ->withArgs(['authService'])
            ->andReturns($authServiceMock)
            ->getMock()
            ->shouldReceive('getService')
            ->once()
            ->withArgs(['request'])
            ->andReturns($requestMock)
            ->getMock();
    }
}