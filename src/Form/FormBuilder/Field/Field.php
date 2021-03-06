<?php

namespace Application\Form\FormBuilder\Field;

use Application\Factory\Factory;

abstract class Field
{
    protected $name;
    protected $type;
    protected $label;
    protected $attributes = [];
    protected $options;
    protected $value;

    /**
     * @param $name
     * @param $type
     * @param $options
     * @param $attributes
     * @return Field
     * @throws FieldException
     */
    public static function instance($name, $type, $options, $attributes): Field
    {
        /** @var Field $field */
        $field = Factory::getInstance($type, [$options]);
        $field->setType($type);
        $field->setName($name);
        $field->setAttributes($attributes);

        if(!$field->isValid())
        {
            throw new FieldException(sprintf('Invalid options for field %s', $type));
        }

        return $field;
    }

    public function __construct($options = [])
    {
        $this->options = $options;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): self
    {
        $this->type = $type;

        return $this;
    }

    public function setAttributes($attributes = []): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getAttributesAsString(): string
    {
        $attrs = '';
        foreach($this->attributes as $name => $attr)
        {
            $attrs .= sprintf(' %s="%s"', $name, $attr);
        }

        return $attrs;
    }

    public function getOption($option)
    {
        return isset($this->options[$option]) ? $this->options[$option] : null;
    }

    public function setOption($option, $value): self
    {
        $this->options[$option] = $value;

        return $this;
    }

    public function isValid(): bool
    {
        return true;
    }

    public function getLabel(): string
    {
        return $this->getOption('label');
    }

    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getValue()
    {
        $this->value;
    }
}