<?php

namespace Application\Service\Request;

use Application\Service\Cookie\Cookie;
use Application\Service\Session\Session;
use PHPUnit\Framework\TestCase;
use Mockery as m;

class RequestTest extends TestCase
{
    public function testShouldGetRequestMethod()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $request = new Request($sessionMock, $cookieMock);
        $this->assertEquals('PUT', $request->getRequestMethod());
    }

    public function testShouldGetRequestUrl()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $_SERVER['REQUEST_URI'] = '/test/123/321/123';
        $request = new Request($sessionMock, $cookieMock);
        $this->assertEquals('/test/123/321/123', $request->getRequestUri());
    }

    public function testShouldCheckIfIsPostRequestMethod()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $_SERVER['REQUEST_METHOD'] = 'post';
        $request = new Request($sessionMock, $cookieMock);
        $this->assertTrue($request->isPost());
    }

    public function testShouldManageServerGlobal()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $_SERVER['test123'] = 'test123';
        $request = new Request($sessionMock, $cookieMock);
        $this->assertEquals('test123', $request->server('test123'));
    }

    public function testShouldManageGetGlobal()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $_GET['test123'] = 'test123';
        $request = new Request($sessionMock, $cookieMock);
        $this->assertEquals('test123', $request->get('test123'));
    }

    public function testShouldManagePostGlobal()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $_POST['test123'] = 'test123';
        $request = new Request($sessionMock, $cookieMock);
        $this->assertEquals('test123', $request->post('test123'));
    }

    public function testShouldConstructRequest()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $request = new Request($sessionMock, $cookieMock);
        $this->assertInstanceOf(Request::class, $request);
    }

}


