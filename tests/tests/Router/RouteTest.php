<?php

class RouteTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldConstructRoute()
    {
        $route = new \Application\Router\Route([], []);

        $this->assertInstanceOf(\Application\Router\Route::class, $route);
    }

    public function testShouldCreateRoute()
    {
        $route = new \Application\Router\Route(['ControllerName', 'ControllerAction', \Application\Router\Route::ACCESS_PUBLIC], ['test', 'test2']);

        $this->assertEquals('ControllerName', $route->getController());
        $this->assertEquals('ControllerAction', $route->getAction());
        $this->assertEquals(['test', 'test2'], $route->getParameters());
        $this->assertEquals(\Application\Router\Route::ACCESS_PUBLIC, $route->getAccess());
    }

    public function testShouldCreatePrivateAccessRoute()
    {
        $route = new \Application\Router\Route([
            'ControllerName', 'ControllerAction', \Application\Router\Route::ACCESS_PRIVATE
        ], ['test', 'test2']);

        $this->assertEquals('ControllerName', $route->getController());
        $this->assertEquals('ControllerAction', $route->getAction());
        $this->assertEquals(['test', 'test2'], $route->getParameters());
        $this->assertEquals(\Application\Router\Route::ACCESS_PRIVATE, $route->getAccess());
    }
}