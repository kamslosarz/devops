<?php

namespace Application\Form\FormBuilder;

use Application\Form\FormBuilder\Field\Field;
use Application\Form\FormBuilder\Field\FieldException;

class FormBuilder
{
    private $fields;

    /**
     * @param $name
     * @param $type
     * @param $options
     * @param $attributes
     * @return $this
     * @throws FieldException
     */
    public function addField($name, $type, $options = [], $attributes = []): self
    {
        $this->fields[$name] = Field::instance($name, $type, $options, $attributes);

        return $this;
    }

    public function getField($name): Field
    {
        return $this->fields[$name];
    }

    public function getFields(): array
    {
        return $this->fields;
    }
}