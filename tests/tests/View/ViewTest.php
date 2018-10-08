<?php

namespace tests\View;

use Application\Config\Config;
use Application\Service\ServiceContainer\ServiceContainer;
use Application\View\View;
use Application\View\ViewElement;
use PHPUnit\Framework\TestCase;
use Mockery as m;


class ViewTest extends TestCase
{
    public function setUp()
    {
        Config::set([
            'twig' => [
                'loader' => [
                    'templates' => FIXTURE_DIR . '/resource',
                    'cache' => false
                ]
            ]
        ]);

        return parent::setUp();
    }

    public function testShouldRenderView()
    {
        $view = new View($this->getServiceContainerMock());

        $viewElement = m::mock(ViewElement::class)
            ->shouldReceive('getViewName')
            ->andReturn('test')
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn(['test' => 'testvar'])
            ->getMock();

        $results = $view->render($viewElement);

        $this->assertEquals('<br>test twig file</br>testvar', $results);
    }

    private function getServiceContainerMock()
    {
        return m::mock(ServiceContainer::class)
            ->shouldReceive('getService')
            ->with('logger')
            ->andReturn(
                m::mock('logger')
                    ->shouldReceive('log')
                    ->getMock()
            )
            ->getMock();
    }

    public function testShouldReturnErrorView()
    {
        $view = new View($this->getServiceContainerMock());
        $viewElement = m::mock(ViewElement::class)
            ->shouldReceive('getViewName')
            ->andReturn('malversed-file')
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn([])
            ->getMock();

        $results = $view->render($viewElement);

        $this->assertEquals('ERROR Unexpected &quot;]&quot;.', $results);
    }

    public function testShouldHandleNotExistingViewFile()
    {
        $view = new View($this->getServiceContainerMock());
        $viewElement = m::mock(ViewElement::class)
            ->shouldReceive('getViewName')
            ->andReturn('not-existing-file')
            ->getMock()
            ->shouldReceive('getParameters')
            ->andReturn([])
            ->getMock();

        $results = $view->render($viewElement);

        $this->assertNull($results);
    }
}