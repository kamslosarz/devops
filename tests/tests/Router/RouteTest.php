<?php

class RouteTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldConstructRoute()
    {
        $route = new \Application\Router\Route('app_user_login', [
            'controller' => 'Controller\UserController',
            'action' => 'loginAction',
            'access' => \Application\Router\Route::ACCESS_PUBLIC,
            'url' => 'test/test'
        ], []);

        $this->assertInstanceOf(\Application\Router\Route::class, $route);
    }

    public function testShouldCreateRoute()
    {
        $route = new \Application\Router\Route('app_test', [
            'controller' => 'ControllerName',
            'action' => 'ControllerAction',
            'access' => \Application\Router\Route::ACCESS_PUBLIC,
            'url' => 'test/test'
        ], ['test', 'test2']);

        $this->assertEquals('ControllerName', $route->getController());
        $this->assertEquals('ControllerAction', $route->getAction());
        $this->assertEquals(['test', 'test2'], $route->getParameters());
        $this->assertEquals(\Application\Router\Route::ACCESS_PUBLIC, $route->getAccess());
    }

    public function testShouldCreatePrivateAccessRoute()
    {
        $route = new \Application\Router\Route('app_test2', [
            'controller' => 'ControllerName',
            'action' => 'ControllerAction',
            'access' => \Application\Router\Route::ACCESS_PRIVATE,
            'url' => 'test/test'
        ], ['test', 'test2']);

        $this->assertEquals('ControllerName', $route->getController());
        $this->assertEquals('ControllerAction', $route->getAction());
        $this->assertEquals(['test', 'test2'], $route->getParameters());
        $this->assertEquals(\Application\Router\Route::ACCESS_PRIVATE, $route->getAccess());
    }
}