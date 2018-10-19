<?php

namespace Application\Annotations\Converter;

use Application\Annotations\Annotation;
use Application\Annotations\Converter\Types\ConverterType;
use Application\Router\Dispatcher\ControllerParameters;

class ConverterAnnotation extends Annotation
{
    public function annotate(ControllerParameters $controllerParameters)
    {
        $converterType = self::getConverterTypeInstance($this->options);

        if($converterType instanceof ConverterType && $converterType->isValid())
        {
            $controllerParameters->addParameterToOverride($this->parameterName, $converterType($this->parameterValue));
        }
    }

    /**
     * @param $options
     * @return mixed
     */
    private static function getConverterTypeInstance($options)
    {
        $converterClass = sprintf('\Application\Annotations\Converter\Types\%sConverter', $options->type);

        if(class_exists($converterClass))
        {
            return new $converterClass($options);
        }

        return null;
    }
}