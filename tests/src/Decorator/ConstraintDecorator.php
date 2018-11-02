<?php

namespace Test\Decorator;

use Application\ParameterHolder\Constraint\Constraint;

class ConstraintDecorator extends Constraint
{
    public function isValid(): bool
    {
        return true;
    }
}