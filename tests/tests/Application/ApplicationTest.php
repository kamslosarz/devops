<?php

use Mockery as m;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function testShouldInvokeApplication()
    {
        $containerMock = m::mock(\Application\Container\Container::class)
            ->shouldReceive('__invoke')
            ->andReturnSelf()
            ->getMock()
            ->shouldReceive('getResponse')
            ->andReturn(m::mock(\Application\Response\Response::class))
            ->getMock();

        $application = new \Application\Application('_test', $this->getServiceContainerConfig());
        $application->setContainer($containerMock);

        $response = $application();

        $this->assertInstanceOf(\Application\Response\Response::class, $response);
        $containerMock->shouldHaveReceived('__invoke')->once();
    }

    public function testShouldSetDifferentEnvironments()
    {
        $enviroment = '_test';
        $this->assertEquals($enviroment, \Application\Application::getEnvironment());
        $this->assertTrue(\Application\Application::isTest());

        $enviroment = '_prod';
        \Application\Application::setEnvironment($enviroment);
        $this->assertEquals('_prod', \Application\Application::getEnvironment());
    }
}