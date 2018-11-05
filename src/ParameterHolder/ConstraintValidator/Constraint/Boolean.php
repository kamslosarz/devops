<?php

namespace Application\ParameterHolder\ConstraintValidator\Constraint;

class Boolean extends Constraint
{
    public function isValid($value): bool
    {
        return is_bool($value);
    }
}
