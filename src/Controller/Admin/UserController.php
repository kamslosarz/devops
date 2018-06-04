<?php

namespace Application\Controller\Admin;

use Application\Container\Appender\Appender;
use Application\Container\Appender\AppenderLevel;
use Application\Form\User\LoginForm;
use Application\Response\Response;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Service\AuthService\AuthService;
use Application\Service\ServiceContainer\ServiceContainer;

class UserController extends Controller
{
    /**
     * @return Response|RedirectResponse
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function loginAction()
    {
        $form = new LoginForm();
        $request = $this->getRequest();

        /** @var AuthService $authService */
        $authService = $this->getService('authService');

        if($authService->isAuthenticated())
        {
            return new RedirectResponse('Admin\AdminController:index');
        }

        if($request->isPost())
        {
            $form->handle($request);

            if($authService->authenticate($form->getData('username'), $form->getData('password')))
            {
                $this->addMessage('Successfully logged in', AppenderLevel::SUCCESS);

                return new RedirectResponse('Admin\AdminController:index');
            }

            $this->addMessage('User not found', AppenderLevel::ERROR);
        }

        return new Response([
            'form' => $form->renderView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @throws \Application\Router\RouteException
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function logoutAction()
    {
        /** @var AuthService $authService */
        $authService = $this->getService('authService');
        $authService->clearSession();
        $this->addMessage('Successfully logged out', AppenderLevel::SUCCESS);

        return new RedirectResponse('Admin\UserController:login');
    }

    public function indexAction()
    {
        return new Response();
    }
}