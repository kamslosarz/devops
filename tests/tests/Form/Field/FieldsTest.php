<?php

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\Fields\Button;
use Application\Form\FormBuilder\Field\Fields\Input;
use Application\Form\FormBuilder\Field\Fields\Select;
use Application\Form\FormBuilder\Field\Fields\Textarea;
use Application\Form\FormBuilder\Field\FieldTypes;

class FieldsTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @throws \Application\Form\FormBuilder\Field\FieldException
     */
    public function testField()
    {
        $input = Field::instance('input', FieldTypes::INPUT, [
        ], [
            'type' => 'text',
            'class' => 'test123'
        ]);

        $this->assertEquals($input->getTagname(), 'input');
        $this->assertEquals($input->getAttributesAsString(), ' type="text" class="test123"');

    }

    public function testSelect()
    {
        $select = new Select([
            'options' => [
                'test' => 'test123'
            ]
        ]);

        $this->assertInstanceOf(Select::class, $select);
        $this->assertEquals($select->getTagname(), 'select');
        $this->assertEquals($select->getOptions(), ['test' => 'test123']);
    }

    public function testInput()
    {
        $input = new Input([
            'type' => 'text'
        ]);

        $this->assertInstanceOf(Input::class, $input);
        $this->assertEquals($input->getTagname(), 'input');
    }

    public function testButton()
    {
        $button = new Button([
            'type' => 'submit'
        ]);

        $this->assertInstanceOf(Button::class, $button);
        $this->assertEquals($button->getTagname(), 'button');
    }

    public function testTextarea()
    {
        $textarea = new Textarea();

        $this->assertInstanceOf(Textarea::class, $textarea);
        $this->assertEquals($textarea->getTagname(), 'textarea');
    }
}