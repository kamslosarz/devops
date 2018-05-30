<?php

use \Mockery as m;

class AdminControllerTest extends \Test\TestCase\ControllerTestCase
{
    /**
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function testShouldRenderIndexAction()
    {
        $adminController = new \Application\Controller\Admin\AdminController($this->getContainerMock(), $this->getAppenderMock());
        $response = $adminController->indexAction();

        $this->assertEquals([], $response);
    }

    public function getDataSet()
    {
        return $this->createArrayDataSet([]);
    }
}