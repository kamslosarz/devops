<?php

use Application\Router\Route;
use Application\Service\Request\RequestMethods;

return [
    'app_admin_index' => [
        'controller' => \Application\Controller\Admin\AdminController::class,
        'action' => 'indexAction',
        'url' => '/'
    ],
    'app_admin_login' => [
        'controller' => \Application\Controller\Admin\UserController::class,
        'action' => 'loginAction',
        'url' => '/admin/login',
        'method' => RequestMethods::GET & RequestMethods::POST,
        'access' => Route::ACCESS_PUBLIC,
    ],
    'app_admin_logout' => [
        'controller' => \Application\Controller\Admin\UserController::class,
        'action' => 'logoutAction',
        'url' => '/admin/logout'
    ],
    'app_admin_project' => [
        'controller' => \Application\Controller\Admin\ProjectController::class,
        'action' => 'indexAction',
        'url' => '/admin/project'
    ],
    'app_admin-project_edit' => [
        'controller' => \Application\Controller\Admin\ProjectController::class,
        'action' => 'projectAction',
        'url' => '/admin/project/edit/[project]'
    ],
    'app_admin_user' => [
        'controller' => \Application\Controller\Admin\UserController::class,
        'action' => 'indexAction',
        'url' => '/admin/user'
    ],
    'app_admin_user_edit' => [
        'controller' => \Application\Controller\Admin\UserController::class,
        'action' => 'editAction',
        'url' => '/admin/user/edit/[user]'
    ]
];