<?php

class RouterTest extends \PHPUnit\Framework\TestCase
{

    public function testMatch()
    {
        $router = new \Application\Router\Router('/admin/test/1/test');

        /** @var \Application\Router\Route $route */
        $route = $router();

        $this->assertEquals('Admin\IndexController', $route->getController());
        $this->assertEquals('indexAction', $route->getAction());
        $this->assertEquals(['id' => 1, 'action' => 'test'], $route->getParameters());

    }

    public function testGetRouteByParameters()
    {
        $router = new \Application\Router\Router();

        $relativeUrl = $router->getRouteByParameters('Admin\ProjectController', 'projectAction', ['id' => 9999]);

        $this->assertEquals('/admin/project/edit/9999', $relativeUrl);
    }

}