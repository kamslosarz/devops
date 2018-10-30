<?php

namespace tests\View;

use Application\Config\Config;
use Application\Response\Response;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;
use Application\View\ViewElement;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use Test\TestCase\Traits\ServiceContainerMockBuilderTrait;


class ViewTest extends TestCase
{
    use ServiceContainerMockBuilderTrait;

    public function getConfig()
    {
        return [
            'loader' => [
                'templates' => FIXTURE_DIR . '/resource',
                'cache' => false
            ]
        ];
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function testShouldRenderView()
    {
        $configMock = m::mock(\Application\Service\Config\Config::class);
        $configMock->twig = $this->getConfig();

        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setConfigMock($configMock);

        $view = new View($serviceContainerMockBuilder->build());

        $responseMock = m::mock(Response::class)
            ->shouldReceive('getResource')
            ->andReturn('test.html.twig')
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn([
                'test' => 'test123123123'
            ])
            ->getMock();

        $results = $view->render($responseMock);

        $this->assertEquals('<br>test twig file</br>test123123123', $results);
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function testShouldReturnErrorView()
    {
        $configMock = m::mock(\Application\Service\Config\Config::class);
        $configMock->twig = $this->getConfig();

        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setConfigMock($configMock);

        $view = new View($serviceContainerMockBuilder->build());

        $results = $view->render(m::mock(Response::class)
            ->shouldReceive('getResource')
            ->andReturn('malversed-file.html.twig')
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn([])
            ->getMock());

        $this->assertEquals('ERROR Unexpected &quot;]&quot;.', $results);
    }

    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Application\View\Twig\TwigFactoryException
     */
    public function testShouldHandleNotExistingViewFile()
    {
        $configMock = m::mock(\Application\Service\Config\Config::class);
        $configMock->twig = $this->getConfig();
        $serviceContainerMockBuilder = $this->getServiceContainerMockBuilder();
        $serviceContainerMockBuilder->setConfigMock($configMock);

        $view = new View($serviceContainerMockBuilder->build());
        $results = $view->render(m::mock(Response::class)
            ->shouldReceive('getResource')
            ->andReturn('not-existing-file')
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn([])
            ->getMock());

        $this->assertThat($results, self::equalTo('ERROR Unable to find template &quot;not-existing-file&quot; (looked into: /mnt/b4517e53-fc73-47a8-a901-625aa901c804/devops/tests/fixture/resource).'));
    }
}