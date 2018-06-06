<?php

namespace Application\Controller\Admin;

use Application\Response\Response;

class AdminController extends Controller
{
    public function indexAction($integer, $string)
    {
        return new Response([
            $integer, $string
        ]);
    }

}
