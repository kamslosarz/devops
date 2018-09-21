<?php

namespace Application\Annotations;

use Application\Annotations\Converter\Converter;

class AnnotationsMap
{
    private static $map = [
        '@convert' => Converter::class
    ];

    public static function getMap()
    {
        return self::$map;
    }
}