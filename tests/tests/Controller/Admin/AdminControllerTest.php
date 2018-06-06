<?php

class AdminControllerTest extends \Test\TestCase\ControllerTestCase
{
    /**
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function testShouldRenderIndexAction()
    {
        $adminController = new \Application\Controller\Admin\AdminController($this->getServiceContainerMockBuilder()->build(), $this->getAppenderMock());
        $response = $adminController->indexAction();

        $this->assertInstanceOf(\Application\Response\Response::class, $response);
        $this->assertEquals([], $response->getParameters());
    }

    public function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }
}