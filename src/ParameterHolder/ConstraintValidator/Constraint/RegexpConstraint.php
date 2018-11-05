<?php

namespace Application\ParameterHolder\ConstraintValidator\Constraint;

class RegexpConstraint extends Constraint
{
    protected $regexp = '';

    public function isValid($value): bool
    {
        if(!preg_match($this->regexp, $value))
        {
            $this->addError($this->errorMessage);

            return false;
        }

        return true;
    }

    public function getErrorMessage(array $parameters = []): string
    {
        return parent::getErrorMessage(array_unshift($this->regexp, $parameters));
    }

}