<?php

namespace Application\Formatter\Constraint;

abstract class Constraint
{
    protected $string;
    protected $parameterName;
    protected $errors;

    public function __construct($string, $parameterName)
    {
        $this->string = $string;
        $this->parameterName = $parameterName;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    abstract function isValid(): bool;
}