<?php

use Application\Router\Route;
use Application\Service\Request\RequestMethods;


return [
    'routes' => [
        'app_admin_index' => [
            'controller' => \Application\Controller\Admin\AdminController::class,
            'action' => 'indexAction',
            'url' => '/admin/index'
        ],
        'app_admin_login' => [
            'controller' => \Application\Controller\Admin\UserController::class,
            'action' => 'loginAction',
            'url' => '/admin/login',
            'access' => Route::ACCESS_PUBLIC,
            'method' => RequestMethods::GET & RequestMethods::POST
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
        'app_admin_project_edit' => [
            'controller' => \Application\Controller\Admin\ProjectController::class,
            'action' => 'projectAction',
            'url' => '/admin/project/edit/[id]'
        ],
        'app_admin_user' => [
            'controller' => \Application\Controller\Admin\UserController::class,
            'action' => 'indexAction',
            'url' => '/admin/user'
        ],
        'app_admin_user_delete' => [
            'controller' => \Application\Controller\Admin\UserController::class,
            'action' => 'deleteAction',
            'url' => '/admin/user/[user]/delete'
        ],
        'app_admin_user_edit' => [
            'controller' => \Application\Controller\Admin\UserController::class,
            'action' => 'editAction',
            'url' => '/admin/user/edit/[user]'
        ],
        'app_admin_test' => [
            'controller' => \Application\Controller\Admin\AdminController::class,
            'action' => 'testAction',
            'url' => '/admin/test/[id]/test',
        ],
        'app_admin_test_parameters' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'parameterOrderTestAction',
            'url' => '/admin/test/[id]/test/[first]/[second]',
            'access' => Route::ACCESS_PUBLIC,
        ]
    ],
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => dirname(__DIR__) . '/src/Resource',
            'cache' => false
        ]
    ],
    'translator' => [
        'adapter' => 'files',
        'path'=> dirname(__DIR__).'/tests/fixture/langs'
    ],
    'web_dir' => dirname(__DIR__) . '/www'
];