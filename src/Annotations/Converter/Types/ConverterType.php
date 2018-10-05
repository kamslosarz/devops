<?php

namespace Application\Annotations\Converter\Types;

abstract class ConverterType
{
    protected $options;

    public function __construct(\stdClass $options)
    {
        $this->options = $options;
    }
}