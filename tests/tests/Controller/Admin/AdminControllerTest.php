<?php

use Mockery as m;

class AdminControllerTest extends \Test\TestCase\ControllerTestCase
{
    /**
     * @throws \Application\Config\ConfigException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldRenderIndexAction()
    {
        $adminController = new \Application\Controller\Admin\AdminController($this->getServiceContainerMockBuilder()->build(), $this->getRouterMock());
        $response = $adminController->indexAction();

        $this->assertInstanceOf(\Application\Response\Response::class, $response);
        $this->assertEquals([], $response->getParameters());
    }

    public function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }

    private function getRouterMock()
    {
        return m::mock(\Application\Router\Router::class);
    }
}