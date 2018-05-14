<?php

class ApplicationTest extends \Test\TestCase\ControllerTestCase
{
    public function testApplication()
    {
        $dispatcher = $this->getDispatcher();
        $results = $dispatcher->dispatch('/admin/login');

        var_dump($results);


        exit;
    }
}