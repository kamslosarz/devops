<?php

namespace Application\ParameterHolder\Constraint;

class RegexpMatcher extends Constraint
{
    protected $regexp;

    public function isValid(): bool
    {
        if(!preg_match($this->regexp, $this->parameterValue))
        {
            $this->errors[] = sprintf($this->errorMessage, $this->parameterName, $this->parameterValue);

            return false;
        }

        return true;
    }
}