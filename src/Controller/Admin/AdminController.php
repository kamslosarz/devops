<?php

namespace Application\Controller\Admin;

use Application\Model\User;
use Application\Response\Response;

class AdminController extends Controller
{
    public function indexAction()
    {
        return new Response();
    }
}