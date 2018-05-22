<?php

namespace Test\Decorator;

use Application\Router\Route;
use Application\Service\Request\Request;
use Application\Service\Request\RequestMethods;

class RequestDecorator extends Request
{
    protected $get;
    protected $post;
    protected $server;
    protected $session;
    /** @var Route $route */
    protected $route;
    protected $cookie;

    public function getCookie()
    {
        return $this->cookie;
    }

    public function setCookie($key, $value)
    {
        $this->cookie[$key] = $value;
        return $this;
    }

    public function getServer()
    {
        return $this->server;
    }

    public function getPost()
    {
        return $this->post;
    }

    public function getQuery()
    {
        return $this->get;
    }

    public function setServer($key, $value)
    {
        $this->server[$key] = $value;

        return $this;
    }

    public function setPost($key, $value)
    {
        $this->post[$key] = $value;

        return $this;
    }

    public function isPost()
    {
        return strtolower($this->server('REQUEST_METHOD')) === RequestMethods::POST;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function setRoute(Route $route)
    {
        $this->route = $route;

        return $this;
    }

    public function setRequestMethod($requestMethod)
    {
        $this->server['REQUEST_METHOD'] = $requestMethod;

        return $this;
    }
}