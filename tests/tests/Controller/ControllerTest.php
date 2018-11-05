<?php

use Mockery as m;

class ControllerTest extends \PHPUnit\Framework\TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldConstructController()
    {
        $event = m::mock(\Application\EventManager\Event::class);
        $event->shouldReceive('getServiceContainer')
            ->andReturn($this->getServiceContainerMockBuilder()->build())
            ->getMock();

        $controller = new \Test\Decorator\ControllerDecorator($event);

        $this->assertThat($controller, self::isInstanceOf(\Application\Controller\Controller::class));
    }

    /**
     * @throws ReflectionException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldAddMessage()
    {
        $appender = m::mock(Application\Service\Appender\Appender::class)
            ->shouldReceive('append')
            ->with('test message to show', \Application\Service\Appender\AppenderLevel::WARN)
            ->once()
            ->getMock();

        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setAppenderMock($appender);

        $event = m::mock(\Application\EventManager\Event::class);
        $event->shouldReceive('getServiceContainer')
            ->andReturn($serviceContainerMockBuilder->build())
            ->getMock();

        $controller = new \Test\Decorator\ControllerDecorator($event);

        $addMessage = new ReflectionMethod($controller, 'addMessage');
        $addMessage->setAccessible(true);
        $response = $addMessage->invoke($controller, 'test message to show', \Application\Service\Appender\AppenderLevel::WARN);

        $this->assertThat($response, self::isInstanceOf(\Application\Controller\Controller::class));

        $appender->shouldHaveReceived('append')->once();
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
            ->andReturn(m::mock(\Application\Service\ServiceInterface::class))
            ->getMock();

        $controller = $this->getController($serviceContainerMock);
        $method = $this->getMethod($controller, 'getService');
        $method->invokeArgs($controller, [
            'testService'
        ]);

        $serviceContainerMock->shouldHaveReceived('getService')
            ->with('testService')
            ->once();
    }

    /**
     * @doesNotPerformAssertions
     * @param $methodName
     * @param $serviceName
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @dataProvider servicesDataDataProvider
     */
    public function testShouldInvokeGetServices($methodName, $serviceName)
    {
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMock = $serviceContainerMockBuilder->build()
            ->shouldReceive('getService')
            ->with($serviceName)
            ->andReturn(m::mock(\Application\Service\ServiceInterface::class))
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

    /**
     * @param m\MockInterface $serviceContainerMock
     * @return \Test\Decorator\ControllerDecorator
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    private function getController(m\MockInterface $serviceContainerMock)
    {
        $event = m::mock(\Application\EventManager\Event::class);
        $event->shouldReceive('getServiceContainer')
            ->andReturn($serviceContainerMock)
            ->getMock();

        return new \Test\Decorator\ControllerDecorator($event);
    }

    private function getMethod($controller, $method)
    {
        $reflectionMethod = (new ReflectionClass($controller))->getMethod($method);
        $reflectionMethod->setAccessible(true);

        return $reflectionMethod;
    }
}