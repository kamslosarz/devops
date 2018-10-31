<?php

return [
    'defaultAction' => '/admin/index',
    'loginAction' => '/admin/login',
    'twig' => [
        'loader' => [
            'templates' => dirname(__DIR__) . '/src/Resource',
            'cache' => false
        ]
    ],
    'servicesMapFile' => __DIR__ . '/serviceMap.php',
    'web_dir' => dirname(__DIR__) . '/www',
    'environment' => '_dev'
];