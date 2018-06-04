<?php

namespace tests\Router\Dispatcher;

use Application\Container\Appender\Appender;
use Application\Response\Response;
use Application\Router\Dispatcher\Dispatcher;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockTrait;


class DispatcherTest extends TestCase
{
    use ServiceContainerMockTrait;

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

    /**
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    public function testShouldReturnRedirectResponse()
    {
        $method = 'indexAction';
        $parameters = [
            'parameter',
            'test',
            'dataset'
        ];
        $appenderMock = m::mock(Appender::class);

        $dispatcher = new Dispatcher(\Test\Fixture\UserController::class, $method, [
            $this->getServiceContainerMock(),
            $appenderMock
        ]);

        $results = $dispatcher->dispatch($parameters);

        $this->assertEmpty($results);
        $this->assertInstanceOf(Response::class, $dispatcher->getResponse());
        $this->assertEquals(['test'], $dispatcher->getResponse()->getParameters());
    }
}