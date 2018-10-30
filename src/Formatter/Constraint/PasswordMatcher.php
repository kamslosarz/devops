<?php

namespace Application\Formatter\Constraint;

class PasswordMatcher extends RegexpMatcher
{
    protected $regexp = '/\A[\!\@\#\$\%\^\&\*\*\(\)\_\+a-zA-Z_-]+\z/';
    protected $errorMessage = 'Invalid value for <%s>. Must only contains !@#$%^&*()_+a-zA-Z_-';
}
