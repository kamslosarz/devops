<?php

namespace Application\Service\Cookie;

use Application\Service\ServiceInterface;

class Cookie implements ServiceInterface
{
    private $cookie;

    public function __construct()
    {
        $this->cookie = $_COOKIE;
    }

    public function get($key)
    {
        return $this->cookie[$key];
    }

    public function set($key, $value)
    {
        $this->cookie[$key] = $value;

        return $this;
    }

    public function save()
    {
        $_COOKIE = $this->cookie;

        return $this;
    }

    public function clear()
    {
        $_SESSION = null;
        $this->session = null;
        session_destroy();
    }
}