<?php

namespace Test\Decorator;

use Application\Service\Cookie\Cookie;
use Application\Service\Request\Request;
use Application\Service\Request\RequestMethods;
use Application\Service\Session\Session;


class RequestDecorator extends Request
{
    protected $get = null;
    protected $post = null;
    protected $server = null;
    protected $session = null;
    protected $cookie = null;

    public function __construct(Session $session, Cookie $cookie)
    {
        parent::__construct($session, $cookie);

        $this->cookie = [];
        $this->session = [];
    }

    public function getCookie($name = null)
    {
        if(!is_null($name))
        {
            return isset($this->cookie[$name]) ? $this->cookie[$name] : null;
        }

        return $this->cookie;
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

    public function getSession()
    {
        return $this->session;
    }

    public function setPost($key, $value)
    {
        $this->post[$key] = $value;

        return $this;
    }

    public function setCookie($key, $value)
    {
        $this->cookie[$key] = $value;

        return $this;
    }

    public function isPost()
    {
        return strtolower($this->server('REQUEST_METHOD')) === RequestMethods::POST;
    }

    public function setRequestMethod($requestMethod)
    {
        $this->server['REQUEST_METHOD'] = $requestMethod;

        return $this;
    }
}