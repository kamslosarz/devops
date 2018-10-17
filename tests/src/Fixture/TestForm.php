<?php

namespace Test\Fixture;

use Application\Form\Form;
use Application\Form\FormBuilder\Field\FieldTypes;
use Application\Form\FormInterface;
use Application\Service\Request;

class TestForm extends Form implements FormInterface
{
    protected function build()
    {
        return $this->formBuilder->addField('username', FieldTypes::INPUT, [
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
    }

    public function getMethod()
    {
        return Request\RequestMethods::POST;
    }

    public function getAttributes()
    {
        return [
            'action' => $this->router->getRouteByName('app_test_action')->getUrl(),
            'title' => $this->translator->translate('Test Form'),
            'name' => 'test_form',
            'class' => 'test-form'
        ];
    }
}