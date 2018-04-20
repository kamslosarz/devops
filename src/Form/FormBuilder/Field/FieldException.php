<?php

namespace Application\Form\FormBuilder\Field;

use Throwable;

class FieldException extends \Exception
{
    public function __construct($message = "",
        $code = 0,
        Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}