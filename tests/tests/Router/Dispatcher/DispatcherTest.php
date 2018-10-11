<?php

namespace tests\Router\Dispatcher;

use Application\ParameterHolder\ParameterHolder;
use Application\Response\Response;
use Application\Router\Dispatcher\ControllerParameters;
use Application\Router\Dispatcher\Dispatcher;
use Application\Router\Dispatcher\DispatcherException;
use Application\Router\Router;
use Application\Service\Appender\Appender;
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
        $routerMock = m::mock(Router::class);

        $dispatcher = new Dispatcher(UserController::class, 'indexAction', [
            $serviceContainerMock,
            $routerMock
        ]);

        $parameterHolder = m::mock(ParameterHolder::class)
            ->shouldReceive('toArray')
            ->andReturns([])
            ->getMock();
        $dispatcher->dispatch($parameterHolder);

        $this->assertInstanceOf(Dispatcher::class, $dispatcher);
        $this->assertInstanceOf(Response::class, $dispatcher->getResponse());
        $this->assertEquals(['test'], $dispatcher->getResponse()->getParameters());
    }

    /**
     * @throws DispatcherException
     */
    public function testShouldThrowInvalidClassException()
    {
        $this->expectException(DispatcherException::class);
        $this->expectExceptionMessageRegExp('/Controller class \'+[\Sa-zA-Z0-9]+\' not exists/');

        $dispatcher = new Dispatcher('UserController', '');
        $dispatcher->dispatch([]);
    }

    /**
     * @throws DispatcherException
     */
    public function testShouldThrowInvalidMethodException()
    {
        $this->expectException(DispatcherException::class);
        $this->expectExceptionMessage(sprintf('Action \'usersListAction\' not exists in \'%s\'', UserController::class));

        $dispatcher = new Dispatcher(UserController::class, 'usersListAction');
        $dispatcher->dispatch([]);
    }


    /**
     * @throws \Application\Router\Dispatcher\DispatcherException
     */
    public function testShouldReturnRedirectResponse()
    {
        $method = 'indexAction';
        $parameterHolder = m::mock(ParameterHolder::class)
            ->shouldReceive('toArray')
            ->andReturns([
                'parameter',
                'test',
                'dataset'
            ])
            ->getMock();

        $routerMock = m::mock(Router::class);

        $dispatcher = new Dispatcher(\Test\Fixture\UserController::class, $method, [
            $this->getServiceContainerMockBuilder()->build(),
            $routerMock
        ]);

        $results = $dispatcher->dispatch($parameterHolder);

        $this->assertEmpty($results);
        $this->assertInstanceOf(Response::class, $dispatcher->getResponse());
        $this->assertEquals(['test'], $dispatcher->getResponse()->getParameters());
    }
}