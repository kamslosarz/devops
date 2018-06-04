<?php

use \Mockery as m;

class AdminControllerTest extends \Test\TestCase\ControllerTestCase
{
    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldRenderIndexAction()
    {
        $adminController = new \Application\Controller\Admin\AdminController($this->getServiceContainerMock(), $this->getAppenderMock());
        $response = $adminController->indexAction();

        $this->assertInstanceOf(\Application\Response\Response::class, $response);
        $this->assertEquals([], $response->getParameters());
    }

    public function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }
}