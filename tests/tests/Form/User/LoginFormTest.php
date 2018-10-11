<?php

use Mockery as m;

class FormTest extends \PHPUnit\Framework\TestCase
{
    public function testLoginForm()
    {
        $loginForm = new \Application\Form\User\LoginForm(null, $this->getTranslatorMock(), $this->getRouterMock());
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Input::class, $loginForm->getFormBuilder()->getField('password'));
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Input::class, $loginForm->getFormBuilder()->getField('username'));
    }

    private function getTranslatorMock()
    {
        return m::mock(\Application\Service\Translator\Translator::class)
            ->shouldReceive('translate')
            ->andReturnUsing(function ($phrase, $vars = [])
            {
                return $phrase;
            })
            ->getMock();
    }

    private function getRouterMock()
    {
        return m::mock(\Application\Router\Router::class)
            ->shouldReceive('getRouteByName')
            ->andReturn(
                m::mock()
                    ->shouldReceive('getUrl')
                    ->andReturn('/admin/login')
                    ->getMock()
            )->getMock();
    }
}