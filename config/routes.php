<?php

return [
    '/' => [
        [\Application\Controller\Admin\AdminController::class, 'indexAction']
    ],
    '/admin/index' => [
        [\Application\Controller\Admin\AdminController::class, 'indexAction']
    ],
    '/admin/login' => [
        [\Application\Controller\Admin\UserController::class, 'loginAction']
    ],
    '/admin/logout' => [
        [\Application\Controller\Admin\UserController::class, 'logoutAction']
    ],
    '/admin/project' => [
        [\Application\Controller\Admin\ProjectController::class, 'indexAction']
    ],
    '/admin/project/edit/[project]' => [
        [\Application\Controller\Admin\ProjectController::class, 'projectAction']
    ],
    '/admin/users' => [
        [\Application\Controller\Admin\UserController::class, 'indexAction']
    ],
    '/admin/users/edit/[user]' => [
        [\Application\Controller\Admin\UserController::class, 'editAction']
    ],
];