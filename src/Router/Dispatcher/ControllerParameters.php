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

        /** @var Annotation $annotation */
        foreach($this->getAnnotations()->getAnnotations() as $annotation)
        {
            $annotation->annotate($this);
        }

        $this->overrideParameters($this->getAnnotations()->getMethodParameterOrder());
    }

    /**
     * @return Annotations
     * @throws \ReflectionException
     */
    public function getAnnotations()
    {
        if($this->annotations instanceof Annotations)
        {
            return $this->annotations;
        }

        $this->annotations = new Annotations(
            (new \ReflectionClass($this->class))->getMethod($this->method),
            $this->toArray()
        );

        return $this->getAnnotations();
    }

    public function addParameterToOverride($name, $value)
    {
        $this->parametersToOverride[$name] = $value;
    }

    public function getParametersToOverride()
    {
        return $this->parametersToOverride;
    }

    public function overrideParameters($order = [])
    {
        $this->parameters = array_replace(
            array_flip($order),
            array_merge($this->parameters, $this->parametersToOverride)
        );

        return $this;
    }
}