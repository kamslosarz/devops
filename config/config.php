<?php

return [
    'routes' => include 'routes.php',
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