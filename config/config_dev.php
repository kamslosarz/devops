<?php

use Application\Controller\Admin as Admin;
use Application\Router\Route;

return [
    'routes' => [
        '/admin/index' => [
            Admin\AdminController::class,
            'indexAction',
        ],
        '/admin/login' => [
            Admin\UserController::class,
            'loginAction',
            Route::ACCESS_PUBLIC
        ],
        '/admin/logout' => [
            Admin\UserController::class,
            'logoutAction',
        ],
        '/admin/project' => [
            Admin\ProjectController::class,
            'indexAction',
        ],
        '/admin/project/edit/[id]' => [
            Admin\ProjectController::class,
            'projectAction',
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