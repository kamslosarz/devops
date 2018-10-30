<?php

namespace Application\Form\User;

use Application\Form\Form;
use Application\Form\FormBuilder\Field\FieldTypes;
use Application\Form\FormBuilder\FormBuilder;
use Application\Router\Router;
use Application\Service\Request\RequestMethods;

class LoginForm extends Form
{
    public function getAttributes(): array
    {
        return [
            'action' => $this->getUrl('/admin/login'),
            'title' => $this->translate('Please login'),
            'name' => 'login',
            'class' => 'login-form'
        ];
    }

    public function getMethod(): string
    {
        return RequestMethods::POST;
    }

    public function getFormBuilder(): FormBuilder
    {
        return $this->formBuilder;
    }

    protected function build(): FormBuilder
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