<?php

return [
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => FIXTURE_DIR . '/resource',
            'cache' => false
        ]
    ],
    'web_dir' => FIXTURE_DIR . '/www',
    'servicesMapFile' => __DIR__ . '/serviceMap.php',
    'environment' => '_test'
];