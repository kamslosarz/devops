<?php

use Application\Form\FormBuilder\Field\FieldTypes;

class FormBuilderTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    public function testShouldBuildForm()
    {
        $formBuilder = new \Application\Form\FormBuilder\FormBuilder();

        $formBuilder
            ->addField('username', FieldTypes::INPUT, [], [
                'type' => 'text',
            ])
            ->addField('select', FieldTypes::SELECT, [
                'options' => [
                    'test' => 'test123',
                    'test123' => 'test'
                ]
            ], [])
            ->addField('textarea', FieldTypes::TEXTAREA, [], [])
            ->addField('submit', FieldTypes::BUTTON, [], [
                'type' => 'submit'
            ]);

        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Input::class, $formBuilder->getField('username'));
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Select::class, $formBuilder->getField('select'));
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Textarea::class, $formBuilder->getField('textarea'));
        $this->assertInstanceOf(\Application\Form\FormBuilder\Field\Fields\Button::class, $formBuilder->getField('submit'));

    }
}