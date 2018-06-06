<?php

namespace tests\Router\Dispatcher;

use Application\Container\Appender\Appender;
use Application\Response\Response;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Dispatcher\DispatcherException;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Test\Fixture\UserController;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;


class DispatcherTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    /**
     * @throws DispatcherException
     */
    public function testShouldConstructDispatcher()
    {
        $serviceContainerMock = $this->getServiceContainerMockBuilder()->build();
        $appenderMock = m::mock(Appender::class);

        $dispatcher = new Dispatcher(UserController::class, 'indexAction', [
            $serviceContainerMock,
            $appenderMock
        ]);
        $dispatcher->dispatch();

        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertInstanceOf(Response::class, $dispatcher->getResponse());
        $this->assertEquals(['test'], $dispatcher->getResponse()->getParameters());
    }

    /**
     * @dataProvider shouldThrowDispatcherExceptionDataProvider
     */
    public function testShouldThrowDispatcherException($message, $class, $method, $parameters, $messageIsRegexp)
    {
        $this->expectException(DispatcherException::class);
        if($messageIsRegexp)
        {
            $this->expectExceptionMessageRegExp($message);
        }
        else
        {
            $this->expectExceptionMessage($message);
        }

        $dispatcher = new Dispatcher($class, $method);
        $dispatcher->dispatch($parameters);
    }

    public function shouldThrowDispatcherExceptionDataProvider()
    {
        return [
            'Invalid class dataSet' => [
                '/Controller class \'+[\Sa-zA-Z0-9]+\' not exists/',
                m::mock(UserController::class),
                '',
                [],
                true
            ],
            'invalid method data set' => [
                sprintf('Action \'usersListAction\' not exists in \'%s\'', UserController::class),
                UserController::class,
                'usersListAction',
                [],
                false
            ]
        ];
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
            $this->getServiceContainerMockBuilder()->build(),
            $appenderMock
        ]);

        $results = $dispatcher->dispatch($parameters);

        $this->assertEmpty($results);
        $this->assertInstanceOf(Response::class, $dispatcher->getResponse());
        $this->assertEquals(['test'], $dispatcher->getResponse()->getParameters());
    }
}