<?php

namespace Application\Form\FormBuilder\Field\Fields;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\FieldInterface;

class Button extends Field implements FieldInterface
{
    public function getTagname(): string
    {
        return 'button';
    }
}