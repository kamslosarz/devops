<?php

namespace Application\Controller\Admin;

use Application\Controller\Controller;
use Application\Form\Form;
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
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function loginAction(): Response
    {
        /** @var Form $form */
        $form = $this->getForm(LoginForm::class);
        $request = $this->getRequest();
        $router = $this->getRouter();
        $config = $this->getConfig();

        /** @var AuthService $authService */
        $authService = $this->getService('auth');

        if($authService->isAuthenticated())
        {
            return new RedirectResponse($router->getUrl($config->defaultAction));
        }

        if($request->isPost())
        {
            $form->handle($request);
            $data = $form->getData();

            if($authService->authenticate($data['username'], $data['password']))
            {
                $this->addMessage($this->getService('translator')
                    ->translate('Successfully logged in'), AppenderLevel::SUCCESS);

                return new RedirectResponse($router->getUrl($config->defaultAction));
            }

            $this->addMessage('User not found', AppenderLevel::ERROR);
        }

        return new Response('admin/user/login.html.twig', [
            'form' => $form->renderView()
        ]);
    }

    /**
     * @return RedirectResponse
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Response\ResponseTypes\RedirectResponseException
     */
    public function logoutAction(): Response
    {
        /** @var AuthService $authService */
        $authService = $this->getService('auth');
        $config = $this->getService('config');
        $router = $this->getRouter();

        $authService->clearSession();
        $this->addMessage($this->getService('translator')->translate('Successfully logged out'), AppenderLevel::SUCCESS);

        return new RedirectResponse($router->getUrl($config->loginAction));
    }

    public function indexAction(): Response
    {
        $users = UserQuery::create()->find();

        return new Response('admin/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @param User $user
     * @return Response
     */
    public function editAction($id): Response
    {
        $user = UserQuery::create()->findOneById($id);

        return new Response('admin/user/edit.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * @param User $user
     * @return Response
     * @throws \Application\Service\ServiceContainer\ServiceContainerException
     * @throws \Propel\Runtime\Exception\PropelException
     */
    public function deleteAction(User $user): Response
    {
        $user->delete();
        $this->addMessage($this->getService('translator')->translate('User was deleted'), AppenderLevel::SUCCESS);

        return new RedirectResponse('/admin/users');
    }
}
