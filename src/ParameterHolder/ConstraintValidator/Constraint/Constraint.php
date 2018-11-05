<?php

namespace Application\ParameterHolder\ConstraintValidator\Constraint;

use Application\ParameterHolder\ConstraintValidator\ConstraintBuilder;

abstract class Constraint
{
    protected $options = [];
    protected $errors = [];
    protected $errorMessage = 'Constraint failed for <%s>';

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getErrorMessage(array $parameters = []): string
    {
        return sprintf($this->errorMessage, ...$parameters);
    }

    protected function addError($error): self
    {
        $this->errors[] = $error;

        return $this;
    }

    public function isNotRequired(): bool
    {
        return $this->options['optional'] ?? false;
    }

    public function build(ConstraintBuilder &$constraintBuilder, $parameterName): void { }

    abstract public function isValid($value): bool;
}