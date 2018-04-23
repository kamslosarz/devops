<?php

namespace Application\Container\Appender;


use Application\Service\Session\Session;

class Appender
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function append($message, $level)
    {
        $this->session->set('messages', (array)$this->session->get('messages') + [$level => $message]);

        return $this;
    }

    public function flashMessages()
    {
        $messages = $this->session->get('messages');
        $this->session->set('messages', []);
        return $messages;
    }

}