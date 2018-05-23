<?php

namespace Test\ControllerDispatcher;

use Application\Service\Session\Session;
use Mockery as m;
use Test\Decorator\RequestDecorator;


class ControllerDispatcher
{
    protected $request;

    public function __construct()
    {
        $sessionMock = m::mock(Session::class);
        $this->request = new RequestDecorator($sessionMock);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function setRequest($request)
    {
        $this->request = $request;

        return $this;
    }

    public function dispatch($route)
    {
        $this->request->setServer('REQUEST_URI', $route);
        $_SERVER = $this->request->getServer();
        $_POST = $this->request->getPost();
        $_GET = $this->request->getQuery();
        $_COOKIE = $this->request->getCookie();

        return (new \Application\Application())();
    }

}