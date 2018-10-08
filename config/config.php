<?php

return [
    'routes' => include 'routes.php',
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => dirname(__DIR__) . '/src/Resource',
//            'cache' => dirname(__DIR__).'/cache/twig'
            'cache' => false
        ]
    ],
    'translator' => [
        'adapter' => 'files',
        'path'=> __DIR__.'/langs'
    ],
    'web_dir' => dirname(__DIR__) . '/www'
];