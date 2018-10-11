<?php

namespace Test\Decorator;

use Application\Controller\Controller;
use Application\Response\Response;
use Application\Response\ResponseTypes\JsonResponse;
use Model\User;

class ControllerDecorator extends Controller
{

    /**
     * @convert('user', options={"type":"Model", "class":"\Model\User"})
     *
     * @param User $user
     * @return Response
     */
    public function testAction(User $user)
    {
        return new Response([
            'user' => $user
        ]);
    }

    public function parameterOrderTestAction($id, $second, $first)
    {
        return new JsonResponse([$id, $second, $first]);
    }

    public function indexAction($test)
    {
        return new Response($test);
    }

    public function returnResponseAction()
    {
        return new Response();
    }
}