<?php

return [
    '/' => [\Application\Controller\Admin\AdminController::class, 'indexAction'],
    '/admin/login' => [\Application\Controller\Admin\UserController::class, 'loginAction'],
    '/admin/logout' => [\Application\Controller\Admin\UserController::class, 'logoutAction'],
    '/admin/project' => [\Application\Controller\Admin\ProjectController::class, 'indexAction'],
    '/admin/project/edit/[project]' => [\Application\Controller\Admin\ProjectController::class, 'projectAction'],
    '/admin/user' => [\Application\Controller\Admin\UserController::class, 'indexAction'],
    '/admin/user/edit/[user]' => [\Application\Controller\Admin\UserController::class, 'editAction'],
    '/test/[param]/action/[id]/12' => [\Test\Decorator\ControllerDecorator::class, 'eventManagerTestAction'],
    '/test/route' => [\Test\Decorator\ControllerDecorator::class, 'testRouteAction'],
];