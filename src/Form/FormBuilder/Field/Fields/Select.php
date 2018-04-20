<?php

namespace Application\Form\FormBuilder\Field\Fields;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\FieldInterface;

class Select extends Field implements FieldInterface
{
    public function getTagname()
    {
        return 'select';
    }

    public function getOptions()
    {
        return $this->options['options'];
    }

    public function isValid()
    {
        return isset($this->options['options']) && is_array($this->options['options']) && !empty($this->options['options']);
    }
}