<?php

namespace Test\Fixture;

use Application\Controller\Admin\Controller;
use Application\Response\Response;

class UserController extends Controller
{
    public function indexAction()
    {
        return new Response(['test']);
    }
}