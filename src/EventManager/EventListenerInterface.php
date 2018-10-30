<?php

namespace Application\EventManager;

interface EventListenerInterface
{
    public function __construct(Event $event);
}