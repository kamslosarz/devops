<?php

return [
    'routes' => [
        '/admin/login' => [
            'Admin\UserController',
            'loginAction',
            \Application\Router\Route::ACCESS_PUBLIC
        ],
        '/admin/logout' => [
            'Admin\UserController',
            'logoutAction',
        ],
        '/admin/index' => [
            'Admin\AdminController',
            'indexAction',
        ],
        '/admin/project' => [
            'Admin\ProjectController',
            'indexAction',
        ],
        '/admin/project/edit/[id]' => [
            'Admin\ProjectController',
            'projectAction',
        ],
        '/admin/test/[id]/[action]' => [
            'Admin\AdminController',
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