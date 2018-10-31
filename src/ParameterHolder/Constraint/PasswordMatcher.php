<?php

namespace Application\ParameterHolder\Constraint;

class PasswordMatcher extends RegexpMatcher
{
    protected $regexp = '/\A[\!\@\#\$\%\^\&\*\*\(\)\_\+a-z0-9A-Z_-]+\z/';
    protected $errorMessage = 'Invalid value for <%s>. Must only contains !@#$%^&*()_+a-zA-Z_-0-9';
}
