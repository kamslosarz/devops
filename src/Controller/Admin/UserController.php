<?php

namespace Application\Controller\Admin;

use Application\Form\User\LoginForm;
use Application\Model\User;

class UserController extends Controller
{
    public function loginAction()
    {
        $user = new User();
        $form = new LoginForm($user);

        return [
            'form' => $form->view()
        ];
    }

    public function indexAction()
    {
        return [];
    }
}