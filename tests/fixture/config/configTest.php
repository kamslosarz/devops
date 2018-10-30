<?php

return [
    'routes' => include 'routes.php',
    'commands' => include 'commands.php',
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => APP_DIR . '/src/Resource',
            'cache' => false
        ]
    ],
    'web_dir' => FIXTURE_DIR . '/www',
    'environment' => '_dev'
];