<?php

namespace Application\Formatter\Constraint;

class RegexpMatcher extends Constraint
{
    protected $regexp;

    public function isValid(): bool
    {
        if(!preg_match($this->regexp, $this->string))
        {
            $this->errors[] = sprintf($this->errorMessage, $this->parameterName, $this->string);

            return false;
        }

        return true;
    }
}