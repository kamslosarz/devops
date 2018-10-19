<?php

use Mockery as m;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldInvokeApplication()
    {
        $containerMock = m::mock(\Application\Container\Container::class)
            ->shouldReceive('__invoke')
            ->andReturnTrue()
            ->getMock()
            ->shouldReceive('getResponse')
            ->andReturn(m::mock(\Application\Response\Response::class))
            ->getMock();

        $application = new \Application\Application();
        $application->setContainer($containerMock);
        $response = $application()->getResponse();

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