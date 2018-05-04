<?php

namespace Application\Service\Session;

use Application\Service\ServiceInterface;

class Session implements ServiceInterface
{
    private $session;

    public function __construct()
    {
        session_start();

        global $_SESSION;
        $this->session = $_SESSION;
    }

    public function set($key, $value)
    {
        $this->session[$key] = $value;

        return $this->save();
    }

    public function get($key)
    {
        return isset($this->session[$key])? $this->session[$key] : null;
    }

    public function save()
    {
        global $_SESSION;
        $_SESSION = $this->session;

        return $this;
    }

    public function clear()
    {
        global $_SESSION;
        $_SESSION = null;
        $this->session = null;
        session_destroy();
    }
}