<?php

namespace Application;

use Application\Request\Request;

final class ApplicationParameters
{
    public $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function requestUri()
    {
        return $this->request->server('REQUEST_URI');
    }
}