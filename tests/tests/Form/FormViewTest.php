<?php

class FormViewTest extends \Test\TestCase\FormViewTestCase
{
    public function testFormViewHelper()
    {
        $loginForm = new \Application\Form\User\LoginForm();
        $formViewHelper = $loginForm->view();

        $this->assertInstanceOf(\Application\Form\FormViewHelper::class, $formViewHelper);
        $this->assertEquals($formViewHelper->getName(), 'login');
        $this->assertEquals($formViewHelper->getMethod(), \Application\Service\Request\RequestMethods::POST);
        $this->assertEquals($formViewHelper->getAction(), '/admin/login');
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     */
    public function testFormRender()
    {
        $loginForm = new \Application\Form\User\LoginForm();
        $formViewHelper = $loginForm->view();

        $domDocument = new \DOMDocument();
        $domDocument->loadHTML($this->getTwig()
            ->render('form/form.html.twig', [
            'form' => $formViewHelper
        ]));

        $inputs = $domDocument->getElementsByTagName('input');

        $this->assertEquals($inputs->item(0)->getAttribute('name'), 'login[login]');
        $this->assertEquals($inputs->item(1)->getAttribute('name'), 'login[password]');
        $this->assertEquals($domDocument->getElementsByTagName('button')->item(0)->getAttribute('type'), 'submit');
    }
}