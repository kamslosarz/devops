<?php

use Application\Controller\Admin as Admin;
use Application\Router\Route;
use Application\Service\Request\RequestMethods;


return [
    'routes' => [
        'app_admin_index' => [
            'controller' => Admin\AdminController::class,
            'action' => 'indexAction',
            'url' => '/admin/index'
        ],
        'app_admin_login' => [
            'controller' => Admin\UserController::class,
            'action' => 'loginAction',
            'url' => '/admin/login',
            'access' => Route::ACCESS_PUBLIC,
            'method' => RequestMethods::GET & RequestMethods::POST
        ],
        'app_admin_logout' => [
            'controller' => Admin\UserController::class,
            'action' => 'logoutAction',
            'url' => '/admin/logout'
        ],
        'app_admin_project' => [
            'controller' => Admin\ProjectController::class,
            'action' => 'indexAction',
            'url' => '/admin/project'
        ],
        'app_admin_project_edit' => [
            'controller' => Admin\ProjectController::class,
            'action' => 'projectAction',
            'url' => '/admin/project/edit/[id]'
        ],
        'app_admin_user' => [
            'controller' => Admin\UserController::class,
            'action' => 'indexAction',
            'url' => '/admin/user'
        ],
        'app_admin_user_delete' => [
            'controller' => Admin\UserController::class,
            'action' => 'deleteAction',
            'url' => '/admin/user/[id]/delete'
        ],
        'app_admin_user_edit' => [
            'controller' => Admin\UserController::class,
            'action' => 'editAction',
            'url' => '/admin/user/edit/[id]'
        ],
        'app_admin_test' => [
            'controller' => Admin\AdminController::class,
            'action' => 'testAction',
            'url' => '/admin/test/[id]/test'
        ]
    ],
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => dirname(__DIR__) . '/src/Resource',
//            'cache' => dirname(__DIR__).'/cache/twig'
            'cache' => false
        ]
    ],
    'web_dir' => dirname(__DIR__) . '/www'
];