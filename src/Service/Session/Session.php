<?php

namespace Application\Service\Session;

use Application\Service\ServiceInterface;

class Session implements ServiceInterface
{
    private $session;

    public function __construct()
    {
        if(!session_id()){
            session_start();
        }
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