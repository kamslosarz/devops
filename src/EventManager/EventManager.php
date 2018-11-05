<?php

namespace Application\EventManager;

use Application\Factory\Factory;

class EventManager
{
    private $listeners = [];

    public function addListeners($event, array $listeners): self
    {
        $this->listeners[$event][] = $listeners;

        return $this;
    }

    public function addListener($event, $listeners): self
    {
        $this->listeners[$event][] = [$listeners];

        return $this;
    }

    public function addSubscriber(SubscriberInterface $subscriber): self
    {
        foreach($subscriber->getSubscribedEvents() as $event => $listeners)
        {
            $this->addListeners($event, $listeners);
        }

        return $this;
    }

    public function hasEvent($eventName): bool
    {
        return isset($this->listeners[$eventName]);
    }

    public function dispatch($eventName, Event $event): void
    {
        foreach($this->listeners[$eventName] as $listeners)
        {
            foreach($listeners as $listener)
            {
                if(is_object($listener))
                {
                    $event->setResponse(call_user_func($listener, $event));
                }
                elseif(isset($listener[0]) && is_callable($listener[0]))
                {
                    $event->setResponse(call_user_func($listener[0], $event));
                }
                else
                {
                    $listener = $this->getListenerStructure($listener);
                    $event->setResponse(call_user_func([
                            Factory::getInstance($listener->instance, [$event, $listener->parameters]),
                            $listener->method,
                        ], ...$event->getParameters())
                    );
                }
            }
        }
    }

    public function getListenerStructure($listener)
    {
        return (object)[
            'instance' => $listener[0],
            'method' => $listener[1],
            'parameters' => $listener[2] ?? null
        ];
    }
}
