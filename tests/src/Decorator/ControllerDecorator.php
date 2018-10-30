<?php

namespace Test\Decorator;

use Application\Controller\Controller;
use Application\Response\Response;
use Application\Response\ResponseTypes\JsonResponse;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\EventManager\ControllerActionEvent;
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

    public function indexAction()
    {
        return new Response([]);
    }

    public function returnResponseAction()
    {
        return new Response();
    }

    public function loginAction()
    {
        return new Response([]);
    }

    public function logoutAction()
    {
        return new RedirectResponse('/admin/login');
    }

    public function eventManagerTestAction($param1, $param2)
    {
        return new Response([
            'eventManagerTestAction',
            $param1,
            $param2
        ]);
    }

    public function testRouteAction()
    {
        return new Response('index.html.twig', ['testRouteAction']);
    }
}