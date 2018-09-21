<?php

namespace Application\Annotations\Converter\Types;

abstract class ConverterType
{
    protected $options;

    public function __construct($options)
    {
        $this->options = $options;
    }
}