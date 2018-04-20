<?php

namespace Application\Form\User;

use Application\Form\Form;
use Application\Form\FormBuilder\Field\FieldTypes;
use Application\Form\FormBuilder\FormBuilder;
use Application\Form\FormInterface;

class LoginForm extends Form implements FormInterface
{
    /**
     * @param FormBuilder $formBuilder
     * @return FormBuilder
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    protected function build(FormBuilder $formBuilder)
    {
        $formBuilder->addField('login', FieldTypes::INPUT, [
            'label' => 'Login'
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

        return $formBuilder;
    }

    public function getAction()
    {
        return '/admin/login';
    }

    public function getName()
    {
        return 'login';
    }

    public function getMethod()
    {
        return Form::METHOD_POST;
    }

    public function getTitle()
    {
        return 'Please login';
    }
}