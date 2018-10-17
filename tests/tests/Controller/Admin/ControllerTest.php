<?php

use Mockery as m;

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    public function testShouldConstructController()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $routerMock = $this->getRouterMock();

        $controller = new \Test\Decorator\ControllerDecorator($serviceContainerMockBuilder->build(), $routerMock);
        $this->assertInstanceOf(\Application\Controller\Controller::class, $controller);
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldAddMessage()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $appenderMock = m::mock(\Application\Service\Appender\Appender::class)
            ->shouldReceive('append')
            ->withArgs([
                'test message',
                'test level'
            ])
            ->once()
            ->getMock();

        $serviceContainerMockBuilder->setAppenderMock($appenderMock);
        $controller = $this->getController($serviceContainerMockBuilder->build());
        $method = $this->getMethod($controller, 'addMessage');
        $results = $method->invokeArgs($controller, [
            'test message',
            'test level'
        ]);

        $appenderMock->shouldHaveReceived('append')
            ->withArgs([
                'test message',
                'test level'
            ])->once();
    }

    /**
     * @doesNotPerformAssertions
     */
    public function testShouldGetService()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMock = $serviceContainerMockBuilder->build()
            ->shouldReceive('getService')
            ->with('testService')
            ->andReturnTrue()
            ->getMock();

        $controller = $this->getController($serviceContainerMock);
        $method = $this->getMethod($controller, 'getService');
        $results = $method->invokeArgs($controller, [
            'testService'
        ]);

        $serviceContainerMock->shouldHaveReceived('getService')
            ->with('testService')
            ->once();
    }

    /**
     * @doesNotPerformAssertions
     * @dataProvider servicesDataDataProvider
     * @param $serviceName
     */
    public function testShouldGetServices($methodName, $serviceName)
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMock = $serviceContainerMockBuilder->build()
            ->shouldReceive('getService')
            ->with($serviceName)
            ->getMock();

        $controller = $this->getController($serviceContainerMock);
        $method = $this->getMethod($controller, $methodName);
        $results = $method->invoke($controller);

        $serviceContainerMock->shouldHaveReceived('getService')
            ->with($serviceName)
            ->once();
    }

    public function servicesDataDataProvider()
    {
        return [
            'Test case request' => [
                'getRequest',
                'request'
            ],
            'Test case translator' => [
                'getTranslator',
                'translator'
            ]
        ];
    }

    public function testShouldGetUser()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAuthServiceMock(
            m::mock(\Application\Service\AuthService\AuthService::class)
                ->shouldReceive('getUser')
                ->andReturn(new \Model\User())
                ->getMock());

        $controller = $this->getController($serviceContainerMockBuilder->build());
        $method = $this->getMethod($controller, 'getUser');

        $results = $method->invoke($controller);
        $this->assertInstanceOf(\Model\User::class, $results);
    }

    public function testShouldConstructForm()
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $controller = $this->getController($serviceContainerMockBuilder->build());
        $method = $this->getMethod($controller, 'getForm');

        $results = $method->invokeArgs($controller, [
            \Test\Fixture\TestForm::class,
            m::mock(\Propel\Runtime\ActiveRecord\ActiveRecordInterface::class)
                ->shouldReceive('getUsername')
                ->andReturn('username')
                ->getMock()
                ->shouldReceive('getSelect')
                ->andReturn('select')
                ->getMock()
                ->shouldReceive('getTextarea')
                ->andReturn('textarea')
                ->getMock()
                ->shouldReceive('getButton')
                ->andReturn('button')
                ->getMock()
        ]);

        $this->assertInstanceOf(\Test\Fixture\TestForm::class, $results);
    }

    private function getRouterMock()
    {
        return m::mock(\Application\Router\Router::class);
    }

    private function getController(m\MockInterface $serviceContainerMock)
    {
        return new \Test\Decorator\ControllerDecorator($serviceContainerMock, $this->getRouterMock());
    }

    private function getMethod($controller, $method)
    {
        $reflectionMethod = (new ReflectionClass($controller))->getMethod($method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }
}