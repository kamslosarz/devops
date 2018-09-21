<?php

namespace Application\Annotations\Converter\Types;

use Model\User;

class ModelConverter extends ConverterType
{
    /**
     * @param $value
     * @return mixed
     */
    public function __invoke($value)
    {
        try
        {
            return eval(sprintf(
                "return %sQuery::create()->filterByPrimaryKey(%s)->findOne();",
                $this->options->class,
                $value));
        }
        catch(\Exception $e)
        {
            throw new AnnotationException('Destination model to convert not found');
        }
    }
}