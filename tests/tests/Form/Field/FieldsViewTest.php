<?php

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\Fields\Button;
use Application\Form\FormBuilder\Field\Fields\Input;
use Application\Form\FormBuilder\Field\Fields\Select;
use Application\Form\FormBuilder\Field\Fields\Textarea;
use Application\Form\FormBuilder\Field\FieldTypes;

class FieldsViewTest extends \Test\TestCase\FormViewTestCase
{
    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    public function testInputView()
    {
        /** @var Input $input */
        $input = Field::instance('test_input', FieldTypes::INPUT, [
            'label' => 'Input label',
        ], [
            'type' => 'text',
            'class' => 'test123',
            'value' => 'test123'
        ]);

        $dom = $this->getDom($this->renderField($input));

        $this->assertTrue($input->isValid());
        $this->assertEquals('Input label', $dom->getElementsByTagName('label')->item(0)->nodeValue);
        $this->assertEquals('test123', $dom->getElementsByTagName('input')->item(0)->getAttribute('value'));
        $this->assertEquals('test_form[test_input]', $dom->getElementsByTagName('input')->item(0)->getAttribute('name'));
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    public function testSelectView()
    {
        /** @var Select $select */
        $select = Field::instance('test_select', FieldTypes::SELECT, [
            'label' => 'select',
            'options' => [
                'test' => 'test123',
                'option_value' => 'test1233'
            ]
        ], [
            'class'=>'select-test'
        ]);

        $dom = $this->getDom($this->renderField($select));

        $this->assertTrue($select->isValid());
        $this->assertEquals('select', $dom->getElementsByTagName('label')->item(0)->nodeValue);
        $this->assertEquals('test_form[test_select]', $dom->getElementsByTagName('select')->item(0)->getAttribute('name'));
        $this->assertEquals('test', $dom->getElementsByTagName('option')->item(0)->getAttribute('value'));
        $this->assertEquals('option_value', $dom->getElementsByTagName('option')->item(1)->getAttribute('value'));
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    public function testButtonView()
    {
        /** @var Button $button */
        $button = Field::instance('test_button', FieldTypes::BUTTON, [
            'label' => 'Submit',
        ], [
            'class'=>'button-test'
        ]);

        $dom = $this->getDom($this->renderField($button));

        $this->assertTrue($button->isValid());
        $this->assertEquals('Submit', $dom->getElementsByTagName('button')->item(0)->nodeValue);
        $this->assertEquals('test_form[test_button]', $dom->getElementsByTagName('button')->item(0)->getAttribute('name'));
    }

    /**
     * @throws Twig_Error_Loader
     * @throws Twig_Error_Runtime
     * @throws Twig_Error_Syntax
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    public function testTextareaView()
    {
        /** @var Textarea $textarea */
        $textarea = Field::instance('test_textarea', FieldTypes::TEXTAREA, [
            'label' => 'Textarea',
        ], [
            'class'=>'textarea-test'
        ]);

        $dom = $this->getDom($this->renderField($textarea));

        $this->assertTrue($textarea->isValid());
        $this->assertEquals('Textarea', $dom->getElementsByTagName('label')->item(0)->nodeValue);
        $this->assertEquals('test_form[test_textarea]', $dom->getElementsByTagName('textarea')->item(0)->getAttribute('name'));
    }
}