<?php

namespace Application\Session;

class Session
{
    private $session;

    public function __construct()
    {
        session_start();
        $this->session = $_SESSION;
    }

    public function set($key, $value)
    {
        $this->session[$key] = $value;
    }

    public function get($key)
    {
        return $this->session[$key];
    }

}