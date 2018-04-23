<?php

namespace Application\Service\Request;

use Application\Service\ServiceInterface;
use Application\Service\Session\Session;

class Request implements ServiceInterface
{
    private $get;
    private $post;
    private $server;
    private $session;

    public function __construct(Session $session)
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->session = $session;
    }

    public function server($key)
    {
        return $this->server[$key];
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
        return strtolower($_SERVER['REQUEST_METHOD']) === RequestMethods::POST;
    }

    public function getSession(){

        return $this->session;
    }

    public function requestUri()
    {
        return $this->server('REQUEST_URI');
    }
}