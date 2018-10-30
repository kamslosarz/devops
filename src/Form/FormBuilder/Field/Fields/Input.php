<?php

namespace Application\Form\FormBuilder\Field\Fields;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\FieldInterface;

class Input extends Field implements FieldInterface
{
    public function getTagname(): string
    {
        return 'input';
    }
}