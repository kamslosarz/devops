<?php

namespace Application\Router\Dispatcher;


use Application\Annotations\Annotation;
use Application\Annotations\Annotations;

class ControllerParameters
{
    private $class;
    private $parameters;
    private $method;

    public function __construct($class, array $parameters = [], $method)
    {
        $this->class = $class;
        $this->parameters = $parameters;

        $annotations = new Annotations((new \ReflectionClass($class))->getMethod($method), $parameters);

        /** @var Annotation $annotation */
        foreach($annotations->getAnnotations() as $annotation)
        {
            $annotation->annotate($this);
        }

        $this->method = $method;
    }

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters = [])
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;

        return $this;
    }

    public function toArray()
    {
        return $this->parameters;
    }
}