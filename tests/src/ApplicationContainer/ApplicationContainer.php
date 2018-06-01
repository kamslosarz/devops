<?php

namespace Test\ApplicationContainer;

use Application\Response\Response;
use Application\Service\Cookie\Cookie;
use Application\Service\Session\Session;
use Mockery as m;
use Test\Decorator\RequestDecorator;


class ApplicationContainer
{
    protected $request;
    private $response;

    public function __construct()
    {
        $sessionMock = m::mock(Session::class);
        $cookieMock = m::mock(Cookie::class);
        $this->request = new RequestDecorator($sessionMock, $cookieMock);
    }

    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return Response
     */
    public function getResponse()
    {
        return $this->response;
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
        $_SESSION = $this->request->getSession();
        $_COOKIE = $this->request->getCookie();

        $this->response = (new \Application\Application())();

        return ($this->response)();
    }

}