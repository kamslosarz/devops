<?php

namespace Application\Controller\Admin;

use Application\Form\User\LoginForm;
use Application\Model\User;
use Application\Service\AuthService\AuthService;

class UserController extends Controller
{
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();

        if($request->isPost())
        {
            $form->handle($request);
        }

        /** @var AuthService $authService */
        $authService = $this->getService('authService');

        if($authService->isAuthenticated($form->getData()))
        {
            return $this->redirect('Admin\AdminController:index', []);
        }

        return [
            'form' => $form->view()
        ];
    }

    public function indexAction()
    {
        return [];
    }
}