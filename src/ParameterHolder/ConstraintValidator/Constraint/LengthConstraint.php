<?php

namespace Application\ParameterHolder\ConstraintValidator\Constraint;

class LengthConstraint extends Constraint
{
    protected $errorMessage = '<%s> must be %s-%s length';

    public function isValid($value): bool
    {
        $valueLength = strlen($value);

        return ($valueLength <= $this->options['max']) && ($valueLength >= $this->options['min']);
    }

    public function getErrorMessage(array $parameters = []): string
    {
        $parameters[] = $this->options['min'];
        $parameters[] = $this->options['max'];

        return parent::getErrorMessage($parameters);
    }
}