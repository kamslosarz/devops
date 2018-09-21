<?php

namespace Application\Annotations\Converter;

use Application\Annotations\Annotation;
use Application\Annotations\AnnotationException;
use Application\Router\Dispatcher\ControllerParameters;

class Converter extends Annotation
{
    public function annotate(ControllerParameters $controllerParameters)
    {
        $converter = self::getInstance($this->options);
        $controllerParameters->setParameter($this->parameterName, $converter($this->parameterValue));
    }

    /**
     * @param $options
     * @return mixed
     * @throws AnnotationException
     */
    private static function getInstance($options)
    {
        $converterClass = sprintf('\Application\Annotations\Converter\Types\%sConverter', $options->type);

        if(class_exists($converterClass))
        {
            return new $converterClass($options);
        }

        throw new AnnotationException(sprintf('Annotation "%s" not exists', $converterClass));
    }
}