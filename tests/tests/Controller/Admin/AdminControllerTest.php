<?php

class AdminControllerTest extends \Test\TestCase\ControllerTestCase
{
    /**
     * @throws \Application\ServiceContainer\ServiceContainerException
     */
    public function testIndexAction()
    {
        $adminController = new \Application\Controller\Admin\AdminController($this->getContainerMock(), $this->getAppenderMock());
        $response = $adminController->indexAction();

        $this->assertEquals([], $response);
    }
}