<?php

use Application\Controller\Admin as Admin;
use Application\Router\Route;


return [
    'routes' => [
        '/admin/login' => [
            Admin\UserController::class,
            'loginAction',
            Route::ACCESS_PUBLIC
        ],
        '/admin/logout' => [
            Admin\UserController::class,
            'logoutAction',
        ],
        '/admin/index' => [
            Admin\AdminController::class,
            'indexAction',
        ],
        '/admin/project' => [
            Admin\ProjectController::class,
            'indexAction',
        ],
        '/admin/project/edit/[id]' => [
            Admin\ProjectController::class,
            'projectAction',
        ],
        '/admin/test/[id]/[action]' => [
            Admin\AdminController::class,
            'testAction',
        ]
    ],
    'defaultAction' => 'Admin\AdminController:index',
    'loginAction' => 'Admin\UserController:login',
    'twig' => [
        'loader' => [
            'templates' => dirname(__DIR__) . '/src/Resource',
//            'cache' => dirname(__DIR__).'/cache/twig'
            'cache' => false
        ]
    ],
    'web_dir' => dirname(__DIR__) . '/www'
];