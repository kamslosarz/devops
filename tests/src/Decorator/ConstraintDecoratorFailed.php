<?php

namespace Test\Decorator;

use Application\ParameterHolder\Constraint\Constraint;

class ConstraintDecoratorFailed extends Constraint
{
    public function isValid(): bool
    {
        $this->errors[] = sprintf('Invalid parameter %s', $this->parameterName);

        return false;
    }
}