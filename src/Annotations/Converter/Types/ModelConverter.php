<?php

namespace Application\Annotations\Converter\Types;

use Propel\Runtime\ActiveRecord\ActiveRecordInterface;

class ModelConverter extends ConverterType
{
    private $class;

    public function __construct(\stdClass $options)
    {
        parent::__construct($options);

        $this->class = $this->getModelQueryClass();
    }

    public function __invoke($value)
    {
        return eval($this->getInvokeCommand($value));
    }

    public function isValid(): bool
    {
        return class_exists($this->class);
    }

    private function getModelQueryClass(): string
    {
        return sprintf('%sQuery', $this->options->class);
    }

    private function getInvokeCommand($value): string
    {
        return sprintf(
            "return %s::create()->filterByPrimaryKey(%d)->findOne();",
            $this->class,
            $value);
    }
}
