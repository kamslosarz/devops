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
        $parameters = [
            'parameter',
            'test',
            'dataset'
        ];

        $classMock = m::mock()
            ->shouldReceive($method)
            ->withArgs($parameters)
            ->once()
            ->andReturns('testResults')
            ->getMock();

        $dispatcher = new Dispatcher($classMock, $method);
        $dispatcher->dispatch($parameters);

        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertEquals('testResults', $dispatcher->getResponse()->getParameters());
    }
}