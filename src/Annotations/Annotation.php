<?php

namespace Application\Annotations;

use Application\Router\Dispatcher\ControllerParameters;

abstract class Annotation
{
    protected $parameterName;
    protected $options;
    protected $parameterValue;

    public function __construct($parameterName, $parameterValue, $options)
    {
        $this->parameterName = $parameterName;
        $this->parameterValue = $parameterValue;
        $this->options = $options;
    }

    /**
     * @param ControllerParameters $controllerParameters
     */
    abstract public function annotate(ControllerParameters $controllerParameters);
}