<?php

namespace Application\Annotations\Converter\Types;


class ModelConverter extends ConverterType
{
    public function __invoke($value)
    {
        return eval(sprintf("return %sQuery::create()->filterByPrimaryKey(%s)->findOne();", $this->options->class, $value));
    }
}