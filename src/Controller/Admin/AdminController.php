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
    public function testAction($integer, $string): Response
    {
        return new Response([
            $integer, $string
        ]);
    }

    /**
     * @return Response
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     */
    public function indexAction(): Response
    {
        return new Response([]);
    }
}
