<?php

use Application\Router\Route;
use Application\Service\Request\RequestMethods;

return [
    'routes' => include APP_DIR . '/config/routes.php',
    'defaultAction' => 'app_admin_index',
    'loginAction' => 'app_admin_login',
    'twig' => [
        'loader' => [
            'templates' => dirname(dirname(__DIR__)) . '/src/Resource',
            'cache' => false
        ]
    ],
    'translator' => [
        'adapter' => 'files',
        'path' => dirname(dirname(__DIR__)) . '/tests/fixture/langs'
    ],
    'web_dir' => FIXTURE_DIR. '/www',
    'servicesMapFIle' => __DIR__ . '/serviceMap.php',
];