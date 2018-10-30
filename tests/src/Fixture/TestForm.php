<?php

namespace Test\Fixture;

use Application\Form\Form;
use Application\Form\FormBuilder\Field\FieldTypes;
use Application\Form\FormBuilder\FormBuilder;
use Application\Form\FormInterface;
use Application\Service\Request;

class TestForm extends Form implements FormInterface
{
    protected function build(): FormBuilder
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

    public function getMethod(): string
    {
        return Request\RequestMethods::POST;
    }

    public function getAttributes(): array
    {
        return [
            'action' => $this->getUrl('/test/route'),
            'title' => $this->translate('Test Form'),
            'name' => 'test_form',
            'class' => 'test-form'
        ];
    }
}