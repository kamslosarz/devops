<?php

use Mockery as m;

class ApplicationTest extends \PHPUnit\Framework\TestCase
{
    use \Test\TestCase\Traits\ServiceContainerMockBuilderTrait;


    public function testShouldConstructApplication()
    {
        $config = [
            'environment' => '_test',
            'servicesMapFile' => FIXTURE_DIR . '/config/serviceMap.php'
        ];
        $application = new \Application\Application($config);

        $this->assertThat($application, self::isInstanceOf(\Application\Application::class));
    }

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

        $application = new \Application\Application(['environment' => '_test'] + $this->getServiceContainerConfig());
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

    public function testShouldSetContainer()
    {
        $containerMock = m::mock(\Application\Container\Container::class);
        $application = new \Application\Application(['environment' => '_test'] + $this->getServiceContainerConfig());

        $application->setContainer($containerMock);
        $this->assertEquals($containerMock, $this->getContainer($application));
    }

    public function getContainer(\Application\Application $application): \Application\Container\Container
    {
        $reflectionClass = new ReflectionClass($application);
        $container = $reflectionClass->getProperty('container');
        $container->setAccessible(true);

        return $container->getValue($application);
    }
}