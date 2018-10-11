<?php

namespace Application\Form\User;

use Application\Form\Form;
use Application\Form\FormBuilder\Field\FieldTypes;
use Application\Router\Router;
use Application\Service\Request\RequestMethods;

class LoginForm extends Form
{
    public function getAttributes()
    {
        return [
            'action' => $this->router->getRouteByName('app_admin_login')->getUrl(),
            'title' => $this->translator->translate('Please login'),
            'name' => 'login',
            'class' => 'login-form'
        ];
    }

    public function getMethod()
    {
        return RequestMethods::POST;
    }

    public function getFormBuilder()
    {
        return $this->formBuilder;
    }

    protected function build()
    {
        return $this->formBuilder->addField('username', FieldTypes::INPUT, [
            'label' => 'Username'
        ], [
            'type' => 'text'
        ])->addField('password', FieldTypes::INPUT, [
            'label' => 'Password'
        ], [
            'type' => 'password'
        ])->addField('submit', FieldTypes::BUTTON, [
            'label' => 'submit'
        ], [
            'type' => 'submit'
        ]);
    }
}