<?php

namespace Test\Fixture;

use Application\Form\Form;
use Application\Form\FormBuilder\Field\FieldTypes;
use Application\Form\FormBuilder\FormBuilder;
use Application\Form\FormInterface;

class TestForm extends Form implements FormInterface
{
    /**
     * @param FormBuilder $formBuilder
     * @return FormBuilder
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    protected function build(FormBuilder $formBuilder)
    {
        $formBuilder->addField('login', FieldTypes::INPUT, [
            'label' => 'Input'
        ], [
            'type' => 'text'
        ])->addField('select', FieldTypes::SELECT, [
            'options' => [
                'test' => 'test',
                'test123' => 'test123'
            ],
            'label' => 'Select option'
        ], [])
            ->addField('textarea', FieldTypes::TEXTAREA, [
                'label' => 'Text area'
            ], [])
            ->addField('button', FieldTypes::BUTTON, [
                'label' => 'Submit'
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
        return 'test_form';
    }

    public function getTitle()
    {

        return 'Test Form';
    }

    public function getMethod()
    {
        return Form::METHOD_POST;
    }
}