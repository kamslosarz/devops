<?php

return [
    'routes' => include 'routes.php',
    'commands' => include 'commands.php',
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => dirname(__DIR__) . '/src/Resource',
            'cache' => false
        ]
    ],
    'servicesMapFIle' => __DIR__ . '/serviceMap.php',
    'web_dir' => dirname(__DIR__) . '/www',
    'environment' => '_dev'
];