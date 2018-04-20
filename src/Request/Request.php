<?php

namespace Application\Request;

final class Request
{
    private $get;
    private $post;
    private $server;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
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
}