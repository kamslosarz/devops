<?php

namespace Application\Service\Session;

use Application\Service\ServiceInterface;

class Session implements ServiceInterface
{
    private $session;

    public function __construct()
    {
        $this->init();
        $this->session = $_SESSION;
    }

    public function set($key, $value): self
    {
        $this->session[$key] = $value;

        return $this->save();
    }

    public function get($key)
    {
        return isset($this->session[$key]) ? $this->session[$key] : null;
    }

    public function save(): self
    {
        $_SESSION = $this->session;

        return $this;
    }

    public function clear($key = null): void
    {
        if(!$key)
        {
            $_SESSION = null;
            $this->session = null;
            @session_destroy();
        }
        else
        {
            $_SESSION[$key] = null;
            $this->session[$key] = null;
        }
    }

    private function init(): void
    {
        if(!session_id())
        {
            @session_start();
        }
    }
}