<?php

namespace Application\Service\Request;

use Application\Service\Router\Route;
use Application\Service\Cookie\Cookie;
use Application\Service\ServiceInterface;
use Application\Service\Session\Session;

class Request implements ServiceInterface
{
    protected $get;
    protected $post;
    protected $server;
    protected $session;
    /** @var Route $route */
    protected $route;
    protected $cookie;

    public function __construct(Session $session, Cookie $cookie)
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->session = $session;
        $this->cookie = $cookie;
    }

    public function server($key)
    {
        return isset($this->server[$key]) ? $this->server[$key] : null;
    }

    public function get($key)
    {
        return $this->get[$key];
    }

    public function post($key)
    {
        return $this->post[$key];
    }

    public function isPost(): bool
    {
        return strtolower($this->server('REQUEST_METHOD')) === RequestMethods::POST;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getCookie(): Cookie
    {
        return $this->cookie;
    }

    public function getRequestUri(): string
    {
        return $this->server('REQUEST_URI');
    }

    public function getRequestMethod(): string
    {
        return $this->server('REQUEST_METHOD');
    }
}


