<?php

namespace Application\ParameterHolder\ConstraintValidator\Constraint;

class PasswordConstraint extends RegexpConstraint
{
    protected $regexp = '/\A[\!\@\#\$\%\^\&\*\*\(\)\_\+a-z0-9A-Z_-]+\z/';
    protected $errorMessage = 'Password failed for <%s>. Allowed: %s';
}
