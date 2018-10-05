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
            $class = $this->getModelQueryClass();

            if(!class_exists($class))
            {
                throw new AnnotationException('Model to convert not exists');
            }

            return eval($this->getInvokeCommand($value, $class));
        }
        catch(\Exception $e)
        {
            throw new AnnotationException(sprintf('Unable to convert model "%s"', $e->getMessage(), $e->getCode(), $e));
        }
    }

    /**
     * @return string
     */
    private function getModelQueryClass()
    {
        return sprintf('%sQuery', $this->options->class);
    }

    /**
     * @param $value
     * @param $class
     * @return string
     */
    private function getInvokeCommand($value, $class)
    {
        return sprintf(
            "return %s::create()->filterByPrimaryKey(%d)->findOne();",
            $class,
            $value);
    }
}
