<?php

namespace Application\Annotations;

use Application\Annotations\Converter\ConverterAnnotation;

class AnnotationsMap
{
    const MAP = [
        '@convert' => ConverterAnnotation::class
    ];
}