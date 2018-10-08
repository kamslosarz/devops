<?php

namespace tests\View;


use Application\Router\Route;
use Application\View\ViewElement;
use PHPUnit\Framework\TestCase;
use Mockery as m;
use Test\Decorator\ControllerDecorator;

class ViewElementTest extends TestCase
{
    public function testShouldConstructViewElement()
    {
        $routeMock = m::mock(Route::class)
            ->shouldReceive('getController')
            ->andReturn(ControllerDecorator::class)
            ->getMock()
            ->shouldReceive('getAction')
            ->andReturn('indexAction')
            ->getMock();

        $viewElement = new ViewElement($routeMock, ['test' => 123]);

        $this->assertEquals('test/decorator/index', $viewElement->getViewName());
        $this->assertEquals(['test' => 123], $viewElement->getParameters());
    }
}