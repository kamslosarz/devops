<?php

class FormTest extends \PHPUnit\Framework\TestCase
{
    public function testLoginForm()
    {
        $loginForm = new \Application\Form\User\LoginForm();
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Input::class, $loginForm->getFormBuilder()->getField('password'));
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Input::class, $loginForm->getFormBuilder()->getField('username'));
    }
}