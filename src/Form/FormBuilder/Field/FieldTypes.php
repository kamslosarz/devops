<?php

namespace Application\Form\FormBuilder\Field;

use Application\Form\FormBuilder\Field\Fields\Button;
use Application\Form\FormBuilder\Field\Fields\Input;
use Application\Form\FormBuilder\Field\Fields\Select;
use Application\Form\FormBuilder\Field\Fields\Textarea;

abstract class FieldTypes
{
    const INPUT = Input::class;
    const TEXTAREA = Textarea::class;
    const SELECT = Select::class;
    const BUTTON = Button::class;
}