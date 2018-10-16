<?php

use Application\Router\Route;
use Application\Service\Request\RequestMethods;


return [
    'routes' => [
        'app_admin_index' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'indexAction',
            'url' => '/admin/index'
        ],
        'app_admin_login' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'loginAction',
            'url' => '/admin/login',
            'access' => Route::ACCESS_PUBLIC,
            'method' => RequestMethods::GET & RequestMethods::POST
        ],
        'app_admin_logout' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'logoutAction',
            'url' => '/admin/logout'
        ],
        'app_admin_project' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'indexAction',
            'url' => '/admin/project'
        ],
        'app_admin_project_edit' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'projectAction',
            'url' => '/admin/project/edit/[id]'
        ],
        'app_admin_user' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'indexAction',
            'url' => '/admin/user'
        ],
        'app_admin_user_delete' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'deleteAction',
            'url' => '/admin/user/[user]/delete'
        ],
        'app_admin_user_edit' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'editAction',
            'url' => '/admin/user/edit/[user]'
        ],
        'app_admin_test' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'testAction',
            'url' => '/admin/test/[id]/test',
        ],
        'app_admin_test_parameters' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'parameterOrderTestAction',
            'url' => '/admin/test/[id]/test/[first]/[second]',
            'access' => Route::ACCESS_PUBLIC,
        ],
        'app_admin_test_test' => [
            'controller' => \Test\Decorator\ControllerDecorator::class,
            'action' => 'returnResponseAction',
            'url' => '/admin/returnResponse',
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
    'web_dir' => dirname(__DIR__) . '/www',
    'servicesMapFIle' => dirname(__DIR__).'/tests/fixture/serviceMap.php',
];