<?php

namespace Application\Service\Request;

use Application\Router\Route;
use Application\Router\Router;
use Application\Service\ServiceInterface;
use Application\Service\Session\Session;

class Request implements ServiceInterface
{
    private $get;
    private $post;
    private $server;
    private $session;
    /** @var Route $route */
    private $route;

    public function __construct(Session $session)
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->session = $session;
    }

    public function server($key)
    {
        return isset($this->server[$key])? $this->server[$key] : null;
    }

    public function get($key)
    {
        return $this->get[$key];
    }

    public function post($key)
    {
        return $this->post[$key];
    }

    public function isPost()
    {
        return strtolower($this->server('REQUEST_URI')) === RequestMethods::POST;
    }

    public function getSession()
    {
        return $this->session;
    }

    public function requestUri()
    {
        return $this->server('REQUEST_URI');
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
}


