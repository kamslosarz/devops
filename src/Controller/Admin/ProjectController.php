<?php

namespace Application\Controller\Admin;

use Application\Container\Appender\AppenderLevel;
use Application\Model\User;

class ProjectController extends Controller
{
    public function indexAction()
    {


        return [];
    }

    public function projectAction($id)
    {
        $user = new User();
        $user->setName('test');
        $entityManager = $this->getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addMessage('User successfully added', AppenderLevel::SUCCESS);

        return [];
    }
}