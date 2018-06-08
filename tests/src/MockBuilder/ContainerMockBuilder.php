<?php

namespace Test\MockBuilder;

use Application\Router\Route;
use Application\Service\AccessChecker\AccessChecker;
use Application\Service\Appender\Appender;
use Application\Service\Cookie\Cookie;
use Application\Service\Logger\Logger;
use Application\Service\Request\Request;
use Mockery as m;

class ContainerMockBuilder
{
    private $sessionMock;
    private $routeMock;
    private $requestMock;
    private $authServiceMock;
    private $accessCheckerMock;
    private $cookieMock;
    private $appenderMock;

    /**
     * @param $cookieMock
     * @return $this
     */
    public function setCookieMock($cookieMock)
    {
        $this->cookieMock = $cookieMock;

        return $this;
    }

    /**
     * @param $accessCheckerMock
     * @return $this
     */
    public function setAccessCheckerMock($accessCheckerMock)
    {
        $this->accessCheckerMock = $accessCheckerMock;

        return $this;
    }

    /**
     * @param $authServiceMock
     * @return $this
     */
    public function setAuthServiceMock($authServiceMock)
    {
        $this->authServiceMock = $authServiceMock;

        return $this;
    }

    /**
     * @param $requestMock
     * @return $this
     */
    public function setRequestMock($requestMock)
    {
        $this->requestMock = $requestMock;

        return $this;
    }

    /**
     * @param $routeMock
     * @return $this
     */
    public function setRouteMock($routeMock)
    {
        $this->routeMock = $routeMock;

        return $this;
    }

    /**
     * @param $sessionMock
     * @return $this
     */
    public function setSessionMock($sessionMock)
    {
        $this->sessionMock = $sessionMock;

        return $this;
    }
    /**
     * @param $appenderMock
     * @return $this
     */
    public function setAppenderMock($appenderMock)
    {
        $this->appenderMock = $appenderMock;

        return $this;
    }

    public function getSessionMock()
    {
        if(!$this->sessionMock)
        {
            $this->sessionMock = m::mock(\Application\Service\Session\Session::class)
                ->shouldReceive('save')
                ->getMock()
                ->shouldReceive('get')
                ->withArgs(['messages'])
                ->andReturn([])
                ->getMock()
                ->shouldReceive('set')
                ->withArgs(['messages', []])
                ->andReturnSelf()
                ->getMock();
        }

        return $this->sessionMock;
    }

    public function getRouteMock()
    {
        if(!$this->routeMock)
        {
            $this->routeMock = m::mock(Route::class)
                ->shouldReceive('getAccess')
                ->andReturns(Route::ACCESS_PUBLIC)
                ->getMock()
                ->shouldReceive('getController')
                ->andReturn('Admin\AdminController')
                ->getMock()
                ->shouldReceive('getAction')
                ->andReturn('index')
                ->getMock();
        }

        return $this->routeMock;
    }

    public function getRequestMock()
    {
        if(!$this->requestMock)
        {
            $this->requestMock = m::mock(Request::class)
                ->shouldReceive('getRequestUri')
                ->andReturns('/admin/index')
                ->getMock()
                ->shouldReceive('getRoute')
                ->andReturns($this->getRouteMock())
                ->getMock()
                ->shouldReceive('setRoute')
                ->andReturns()
                ->getMock();
        }

        return $this->requestMock;
    }

    public function getAuthServiceMock()
    {
        if(!$this->authServiceMock)
        {
            $this->authServiceMock = m::mock(\Application\Service\AuthService\AuthService::class)
                ->shouldReceive('getSession')
                ->andReturns($this->getSessionMock())
                ->getMock()
                ->shouldReceive('isAuthenticated')
                ->andReturns(true)
                ->getMock()
                ->shouldReceive('getRoute')
                ->andReturns($this->getRouteMock())
                ->getMock();
        }

        return $this->authServiceMock;
    }

    public function getAccessCheckerMock()
    {
        if(!$this->accessCheckerMock)
        {
            $this->accessCheckerMock = m::mock(AccessChecker::class)
                ->shouldReceive('hasAccess')
                ->andReturns(true)
                ->getMock();
        }

        return $this->accessCheckerMock;
    }

    public function getCookieMock()
    {
        if(!$this->cookieMock)
        {
            $this->cookieMock = m::mock(Cookie::class)
                ->shouldReceive('save')
                ->once()
                ->andReturnSelf()
                ->getMock();
        }

        return $this->cookieMock;
    }

    public function getAppenderMock()
    {
        if(!$this->appenderMock)
        {
            $this->appenderMock = m::mock(Appender::class)
                ->shouldReceive('append')
                ->andReturnSelf()
                ->getMock();
        }

        return $this->appenderMock;
    }

    public function build()
    {
        return m::mock(\Application\Service\ServiceContainer\ServiceContainer::class)
            ->shouldReceive('getService')
            ->once()
            ->withArgs(['session'])
            ->andReturns($this->getSessionMock())
            ->getMock()
            ->shouldReceive('getService')
            ->withArgs(['auth'])
            ->andReturns($this->getAuthServiceMock())
            ->getMock()
            ->shouldReceive('getService')
            ->withArgs(['request'])
            ->andReturns($this->getRequestMock())
            ->getMock()
            ->shouldReceive('getService')
            ->with('accessChecker')
            ->andReturns($this->getAccessCheckerMock())
            ->getMock()
            ->shouldReceive('getService')
            ->withArgs(['logger'])
            ->andReturns(m::mock(Logger::class)->shouldReceive('log')->getMock())
            ->getMock()
            ->shouldReceive('getService')
            ->withArgs(['cookie'])
            ->andReturns($this->getCookieMock())
            ->getMock()
            ->shouldReceive('getService')
            ->withArgs(['appender'])
            ->andReturns($this->getAppenderMock())
            ->getMock();

    }
}