<?php

namespace Application\Factory;

class Factory
{
    public static function getInstance($class, $parameters = [])
    {
        if(empty($parameters))
        {
            return new $class();
        }

        return new $class(...$parameters);
    }
}