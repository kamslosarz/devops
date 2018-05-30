<?php

class RouterTest extends \PHPUnit\Framework\TestCase
{
    public function testShouldMatchRoute()
    {
        $router = new \Application\Router\Router('/admin/test/1/test');

        /** @var \Application\Router\Route $route */
        $route = $router();

        $this->assertEquals('Admin\IndexController', $route->getController());
        $this->assertEquals('indexAction', $route->getAction());
        $this->assertEquals(['id' => 1, 'action' => 'test'], $route->getParameters());

    }

    public function testShouldGetRouteByParameters()
    {
        $router = new \Application\Router\Router();

        $relativeUrl = $router->getRouteByParameters('Admin\ProjectController', 'projectAction', ['id' => 9999]);

        $this->assertEquals('/admin/project/edit/9999', $relativeUrl);
    }

    /**
     * @throws \Application\Router\RouteException
     */
    public function testShouldReturnRouteException()
    {
        $fakeRoute = '/this/route/not/exists';
        $this->expectException(\Application\Router\RouteException::class);
        $this->expectExceptionMessage(sprintf('Route \'%s\' not found', $fakeRoute));
        $router = new \Application\Router\Router($fakeRoute);
        $router();
    }

    public function testShouldReturnCompactRouteName()
    {
        $compactName = \Application\Router\Router::getCompactRouteName('TestController', 'testAction');

        $this->assertEquals('TestController:test', $compactName);
    }


}