<?php

use Mockery as m;

class FormViewTest extends \Test\TestCase\FormViewTestCase
{
    public function testShouldRenderFormView()
    {
        $loginForm = new \Application\Form\User\LoginForm(null, $this->getTranslatorMock(), $this->getRouterMock());
        $formView = $loginForm->renderView();

        $this->assertInstanceOf(\Application\Form\FormView::class, $formView);
        $this->assertEquals($formView->getName(), 'login');
        $this->assertEquals($formView->getMethod(), \Application\Service\Request\RequestMethods::POST);
        $this->assertEquals($formView->getAction(), '/admin/login');
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testShouldRenderForm()
    {
        $loginForm = new \Application\Form\User\LoginForm(null, $this->getTranslatorMock(), $this->getRouterMock());
        $formView = $loginForm->renderView();

        $domDocument = new \DOMDocument();
        $domDocument->loadHTML($this->getTwig()
            ->render('form/form.html.twig', [
                'form' => $formView
            ]));


        $inputs = $domDocument->getElementsByTagName('input');

        $this->assertEquals($inputs->item(0)->getAttribute('name'), 'login[username]');
        $this->assertEquals($inputs->item(1)->getAttribute('name'), 'login[password]');
        $this->assertEquals($domDocument->getElementsByTagName('button')->item(0)->getAttribute('type'), 'submit');
    }

    private function getTranslatorMock()
    {
        return m::mock(\Application\Service\Translator\Translator::class)
            ->shouldReceive('translate')
            ->andReturnUsing(function ($phrase, $vars = []) {
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