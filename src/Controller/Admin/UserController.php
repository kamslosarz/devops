<?php

namespace Application\Controller\Admin;

use Application\Config\Config;
use Application\Form\User\LoginForm;
use Application\Response\Response;
use Application\Response\ResponseTypes\RedirectResponse;
use Application\Service\Appender\AppenderLevel;
use Application\Service\AuthService\AuthService;
use Model\User;
use Model\UserQuery;

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
        $authService = $this->getService('auth');

        if($authService->isAuthenticated())
        {
            return new RedirectResponse(Config::get('defaultAction'));
        }

        if($request->isPost())
        {
            $form->handle($request);

            if($authService->authenticate($form->getData('username'), $form->getData('password')))
            {
                $this->addMessage($this->getService('translator')->translate('Successfully logged in'), AppenderLevel::SUCCESS);

                return new RedirectResponse(Config::get('defaultAction'));
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
        $authService = $this->getService('auth');
        $authService->clearSession();
        $this->addMessage($this->getService('translator')->translate('Successfully logged out'), AppenderLevel::SUCCESS);

        return new RedirectResponse(Config::get('loginAction'));
    }

    public function indexAction()
    {
        $users = UserQuery::create()->find();

        return new Response([
            'users' => $users
        ]);
    }

    /**
     * @convert('user', options={"type":"Model", "class":"\Model\User"})
     * @param User $user
     * @return Response
     */
    public function editAction(User $user)
    {
        return new Response([
            'user' => $user
        ]);
    }

    /**
     * @convert('user', options={"type":"Model", "class":"\Model\User"})
     * @param User $user
     * @return Response
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteAction(User $user)
    {
        $user->delete();
        $this->addMessage($this->getService('translator')->translate('User was deleted'), AppenderLevel::SUCCESS);

        return new Response();
    }
}
