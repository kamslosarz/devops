<?php

namespace Application\ParameterHolder\ConstraintValidator\Constraint;

use Application\ParameterHolder\ConstraintValidator\ConstraintBuilder;

class UsernameConstraint extends RegexpConstraint
{
    const MAX_LEN = 16;
    const MIN_LEN = 3;

    protected $regexp = '/\A[a-zA-Z_-]+\z/';
    protected $errorMessage = 'Invalid <%s>. Must contains only %s';

    public function build(ConstraintBuilder &$constraintBuilder, $parameterName): void
    {
        $constraintBuilder->addConstraint($parameterName, new LengthConstraint([
            'max' => $this->options['maxLength'] ?? self::MAX_LEN,
            'min' => $this->options['minLength'] ?? self::MIN_LEN
        ]));
    }
}