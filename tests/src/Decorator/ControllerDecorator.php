<?php

namespace Test\Decorator;

use Application\Controller\Controller;
use Application\Response\Response;
use Application\Response\ResponseTypes;
use Application\Response\ResponseTypes\JsonResponse;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Response\ResponseTypes\ErrorResponse;
use Application\Response\ResponseTypes\ConsoleResponse;
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
        return new Response('action.html.twig', [
            'user' => $user
        ]);
    }

    public function parameterOrderTestAction($id, $second, $first)
    {
        return new JsonResponse([$id, $second, $first]);
    }

    public function indexAction()
    {
        return new Response('action.html.twig');
    }

    public function returnResponseAction()
    {
        return new Response('action.html.twig');
    }

    public function loginAction()
    {
        return new Response('action.html.twig');
    }

    public function logoutAction()
    {
        return new RedirectResponse('/admin/login');
    }

    public function testRouteAction()
    {
        return new Response('action.html.twig', ['testRouteAction']);
    }

    public function testDifferentResponses($type)
    {
        $responseData = $this->getRequest()->post('responseData');

        switch($type){
            case ResponseTypes::JSON:
                return new JsonResponse($responseData);
            break;
            case ResponseTypes::CONSOLE:
                return new ConsoleResponse($responseData);
            break;
            case ResponseTypes::ERROR:
                return new ErrorResponse($responseData);
            break;
            case ResponseTypes::REDIRECT:
                return new RedirectResponse($responseData);
            break;
            default:
                return new Response($responseData['resource'], $responseData['parameters']);
            break;
        }
    }
}