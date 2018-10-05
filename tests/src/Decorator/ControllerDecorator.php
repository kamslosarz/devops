<?php

namespace Test\Decorator;

use Application\Controller\Controller;
use Application\Response\Response;
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
}