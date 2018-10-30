<?php

namespace Application\Formatter\Constraint;

class UsernameMatcher extends RegexpMatcher
{
    protected $regexp = '/\A[a-zA-Z_-]+\z/';
    protected $errorMessage = 'Invalid value for <%s> (\'%s\'). Must only contains a-zA-Z_-';
}