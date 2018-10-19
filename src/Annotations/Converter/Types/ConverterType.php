<?php

namespace Application\Annotations\Converter\Types;

abstract class ConverterType
{
    protected $options;

    public function __construct(\stdClass $options)
    {
        $this->options = $options;
    }

    abstract public function isValid(): bool;
    abstract public function __invoke($value);
}