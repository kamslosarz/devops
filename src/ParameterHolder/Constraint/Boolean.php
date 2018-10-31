<?php

namespace Application\ParameterHolder\Constraint;

class Boolean extends Constraint
{
    public function isValid(): bool
    {
        return is_bool($this->parameterValue);
    }
}
