<?php

namespace Application\Controller\Admin;

use Application\Response\Response;

class AdminController extends Controller
{
    public function testAction($integer, $string)
    {
        return new Response([
            $integer, $string
        ]);
    }

    public function indexAction()
    {
        return new Response([]);
    }
}
