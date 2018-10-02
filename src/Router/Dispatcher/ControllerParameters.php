<?php

namespace Application\Router\Dispatcher;


use Application\Annotations\Annotation;
use Application\Annotations\Annotations;
use Application\ParameterHolder\ParameterHolder;

class ControllerParameters extends ParameterHolder
{
    private $class;
    private $method;
    private $parametersToOverride = [];

    public function applyAnnotations($class, $method)
    {
        $this->class = $class;
        $this->method = $method;

        $annotations = new Annotations((new \ReflectionClass($class))->getMethod($method), array_values($this->toArray()));
        /** @var Annotation $annotation */
        foreach($annotations->getAnnotations() as $annotation)
        {
            $annotation->annotate($this);
        }

        $this->overrideParameters();
    }

    public function addParameterToOverride($name, $value)
    {
        $this->parametersToOverride[$name] = $value;
    }

    public function getParametersToOverride()
    {
        return $this->parametersToOverride;
    }

    public function overrideParameters()
    {
        $parameters = array_values($this->parameters);

        foreach($this->parametersToOverride as $id => $parameter)
        {
            $parameters[$id] = $parameter;
        }

        $this->parameters = $parameters;

        return $this;
    }
}