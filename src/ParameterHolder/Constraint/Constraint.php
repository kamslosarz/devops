<?php

namespace Application\ParameterHolder\Constraint;

abstract class Constraint
{
    protected $parameterValue;
    protected $parameterName;
    protected $errors = [];

    public function __construct($parameterValue, $parameterName)
    {
        $this->parameterValue = $parameterValue;
        $this->parameterName = $parameterName;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    abstract public function isValid(): bool;
}