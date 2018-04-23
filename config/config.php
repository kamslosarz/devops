<?php

return [
    'routes' => [
        '/admin/login' => [
            'Admin\UserController',
            'loginAction',
        ],
        '/admin/index' => [
            'Admin\AdminController',
            'indexAction',
        ],
        '/admin/project/index' => [
            'Admin\ProjectController',
            'indexAction',
        ],
        '/admin/project/edit/[id]' => [
            'Admin\ProjectController',
            'projectAction',
        ]
    ],
    'logger' => [
        'ApplicationLogger' => [
            'dir' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR
        ]
    ],
    'doctrine' => [
        'driver' => 'pdo_sqlite',
        'path' => dirname(__DIR__) . '/data/database.db3',
        'models' => dirname(__DIR__).'/src/Model'
    ],
    'twig'=>[
        'loader'=>[
            'templates'=>dirname(__DIR__).'/src/Resource',
//            'cache' => dirname(__DIR__).'/cache/twig'
            'cache' => false
        ]
    ],
    'web_dir'=>dirname(__DIR__).'/www'
];