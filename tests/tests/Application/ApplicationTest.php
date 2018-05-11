<?php

class ApplicationTest extends \Test\TestCase\ControllerTestCase
{
    public function testApplication()
    {
        $_SERVER['REQUEST_URI'] = '/admin/login';
        (new \Application\Application())();
    }
}