<?php

namespace Application\Controller\Admin;

use Application\Container\Appender\AppenderLevel;
use Application\Form\User\LoginForm;
use Application\Service\AuthService\AuthService;

class UserController extends Controller
{
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();

        /** @var AuthService $authService */
        $authService = $this->getService('authService');

        if($authService->isAuthenticated())
        {
            return $this->redirect('Admin\AdminController:index', []);
        }

        if($request->isPost())
        {
            $form->handle($request);

            if($authService->authenticate($form->getData('username'), $form->getData('password')))
            {
                $this->addMessage('Successfully logged in', AppenderLevel::SUCCESS);

                return $this->redirect('Admin\AdminController:index', []);
            }

            $this->addMessage('User not found', AppenderLevel::ERROR);
        }

        return [
            'form' => $form->view()
        ];
    }

    public function logoutAction()
    {
        /** @var AuthService $authService */
        $authService = $this->getService('authService');
        $authService->clearSession();
        $this->addMessage('Successfully logged out', AppenderLevel::SUCCESS);

        return $this->redirect('Admin\UserController:login');
    }

    public function indexAction()
    {
        return [];
    }
}