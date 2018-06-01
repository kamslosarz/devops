<?php

namespace ErrorHandler;


class ErrorHandler
{
    private $exception;

    public function __construct(\Exception $e)
    {
        $this->exception = $e;
    }

    public function __invoke()
    {
        return $this->exception->getTraceAsString();
    }
}