<?php

namespace Test\ControllerDispatcher;

class RequestMock
{
    private $post;
    private $get;
    private $server;

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
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

    public function setPost($key, $value)
    {
        $this->post[$key] = $value;

        return $this;
    }

    public function setQuery($key, $value)
    {
        $this->get[$key] = $value;

        return $this;
    }

    public function setServer($key, $value)
    {
        $this->server[$key] = $value;

        return $this;
    }


}