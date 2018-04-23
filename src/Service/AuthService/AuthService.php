<?php

namespace Application\Service\AuthService;

use Application\Service\Request\Request;
use Application\Service\ServiceInterface;

class AuthService implements ServiceInterface
{
    private $request;
    private $sessionToken;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function isAuthenticated($data)
    {
        $session = $this->request->getSession();



        return true;
    }

    public function getSessionToken()
    {
        return $this->sessionToken;
    }
}