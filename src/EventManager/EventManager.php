<?php

namespace Application\EventManager;

use Application\Factory\Factory;

class EventManager
{
    private $listeners = [];

    public function addListener($event, $listener): self
    {
        $this->listeners[$event][] = $listener;

        return $this;
    }

    public function addSubscriber(SubscriberInterface $subscriber): self
    {
        foreach($subscriber->getSubscribedEvents() as $event => $listener)
        {
            $this->addListener($event, $listener);
        }

        return $this;
    }

    public function hasEvent($eventName): bool
    {
        return isset($this->listeners[$eventName]);
    }

    public function dispatch($eventName, Event $event): void
    {
        foreach($this->listeners[$eventName] as $listener)
        {
            if(!is_array($listener) || is_object($listener[0]))
            {
                $event->setResponse(call_user_func($listener, $event));
            }
            else
            {
                $event->setResponse(call_user_func_array([
                        Factory::getInstance($listener[0], [$event]),
                        $listener[1],
                    ], [$event->getParameters()])
                );
            }
        }
    }
}
