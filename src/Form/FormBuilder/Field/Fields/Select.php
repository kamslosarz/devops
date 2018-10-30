<?php

namespace Application\Form\FormBuilder\Field\Fields;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\FieldInterface;

class Select extends Field implements FieldInterface
{
    public function getTagname(): string
    {
        return 'select';
    }

    public function getOptions(): array
    {
        return $this->options['options'];
    }

    public function isValid(): bool
    {
        return isset($this->options['options']) && is_array($this->options['options']) && !empty($this->options['options']);
    }
}