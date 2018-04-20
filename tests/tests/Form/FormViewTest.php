<?php

class FormViewTest extends \Test\TestCase\FormViewTestCase
{
    public function testFormView()
    {
        $loginForm = new \Application\Form\User\LoginForm();
        $formView = $loginForm->view();

        $this->assertInstanceOf(\Application\Form\FormView::class, $formView);
        $this->assertEquals($formView->getName(), 'login');
        $this->assertEquals($formView->getMethod(), \Application\Form\Form::METHOD_POST);
        $this->assertEquals($formView->getAction(), '/admin/login');
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testFormRender()
    {
        $loginForm = new \Application\Form\User\LoginForm();
        $formView = $loginForm->view();

        $domDocument = new \DOMDocument();
        $domDocument->loadHTML($this->getTwig()
            ->render('form/form.html.twig', [
            'form' => $formView
        ]));

        $inputs = $domDocument->getElementsByTagName('input');

        $this->assertEquals($inputs->item(0)->getAttribute('name'), 'login[login]');
        $this->assertEquals($inputs->item(1)->getAttribute('name'), 'login[password]');
        $this->assertEquals($domDocument->getElementsByTagName('button')->item(0)->getAttribute('type'), 'submit');
    }
}