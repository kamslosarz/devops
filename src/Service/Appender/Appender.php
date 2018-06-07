<?php

namespace Application\Service\Appender;

use Application\Service\ServiceInterface;
use Application\Service\Session\Session;

class Appender implements ServiceInterface
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function append($message, $level)
    {
        $this->session->set('messages', array_merge_recursive([$level => $message], (array)$this->session->get('messages')));

        return $this;
    }

    public function flashMessages()
    {
        $messages = $this->session->get('messages');
        $this->session->set('messages', null);

        return $messages;
    }
}