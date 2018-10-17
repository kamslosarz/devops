<?php

namespace Application\Controller\Admin;

use Application\Controller\Controller;
use Application\Response\Response;

/**
 * Class AdminController
 * @package Application\Controller\Admin
 */
class AdminController extends Controller
{
    public function testAction($integer, $string)
    {
        return new Response([
            $integer, $string
        ]);
    }

    /**
     * @return Response
     */
    public function indexAction()
    {
        return new Response([]);
    }
}
