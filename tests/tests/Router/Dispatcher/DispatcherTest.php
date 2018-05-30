<?php

namespace tests\Router\Dispatcher;

use Application\Router\Dispatcher\Dispatcher;
use Mockery as m;
use PHPUnit\Framework\TestCase;


class DispatcherTest extends TestCase
{
    public function testShouldConstructDispatcher()
    {
        $method = 'testMethod';
        $classMock = m::mock()
            ->shouldReceive($method)
            ->once()
            ->andReturns('testResults');

        $dispatcher = new Dispatcher($classMock, $method);

        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertInstanceOf('testResults', $dispatcher->getResults());
    }
}