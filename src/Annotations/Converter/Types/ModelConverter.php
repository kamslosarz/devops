<?php

namespace Application\Annotations\Converter\Types;


use Application\Annotations\AnnotationException;
use Model\UserQuery;

class ModelConverter extends ConverterType
{
    /**
     * @param $value
     * @return mixed
     * @throws AnnotationException
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