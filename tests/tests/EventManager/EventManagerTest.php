<?php

namespace tests\EventManager;

use Application\EventManager\Event;
use Application\EventManager\EventListenerInterface;
use Application\EventManager\EventManager;
use Application\Response\Response;
use PHPUnit\DbUnit\DataSet\ArrayDataSet;
use Test\TestCase\DatabaseTestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;
use Mockery as m;

class EventManagerTest extends DatabaseTestCase
{
    use ServiceContainerMockBuilderTrait;

    public function testShouldConstructEventManager()
    {
        $eventManager = new EventManager();

        $this->assertThat($eventManager,
            self::isInstanceOf(EventManager::class));
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldAddAndInvokeClosureListener()
    {
        $eventManager = new EventManager();
        $event = m::mock(Event::class)
            ->shouldReceive('setResponse')
            ->with(Response::class)
            ->once()
            ->andReturnSelf()
            ->getMock();

        $eventManager->addListener('onTestEvent', function (Event $event)
        {
            return m::mock(Response::class);
        });

        $eventManager->dispatch('onTestEvent', $event);
        $event->shouldHaveReceived('setResponse')->once();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldAddAndInvokeAnonymousListener()
    {
        $eventManager = new EventManager();
        $event = m::mock(Event::class)
            ->shouldReceive('setResponse')
            ->with(Response::class)
            ->once()
            ->andReturnSelf()
            ->getMock();

        $listener = $this->getAnonimousListener($event);
        $eventManager->addListener('onTestEvent',
            [$listener, 'onTestEvent']);

        $eventManager->dispatch('onTestEvent', $event);
        $event->shouldHaveReceived('setResponse')->once();
    }

    private function getAnonimousListener(Event $event)
    {
        return new class($event) implements EventListenerInterface
        {
            protected $event;

            public function __construct(Event $event)
            {
                $this->event = $event;
            }

            public function onTestEvent(): Response
            {
                return new Response(
                    [
                        'onTestEventCalled' => true,
                    ]
                );
            }
        };
    }

    public function getDataSet()
    {
        return new ArrayDataSet([
            'users' => []
        ]);
    }
}