<?php

return [
    'config' => [
        \Application\Service\Config\Config::class, [
            include __DIR__ . '/config.php'
        ]
    ],
    'logger' => [
        \Application\Service\Logger\Logger::class, [
            [
                'ApplicationLogger' => [
                    'dir' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR,
                    'name' => 'app'
                ],
                'ConsoleLogger' => [
                    'dir' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR,
                    'name' => 'console'
                ]
            ]
        ]
    ],
    'session' => [
        \Application\Service\Session\Session::class, [

        ]
    ],
    'cookie' => [
        \Application\Service\Cookie\Cookie::class, [

        ]
    ],
    'request' => [
        \Application\Service\Request\Request::class, [
            '@session',
            '@cookie'
        ]
    ],
    'auth' => [
        \Application\Service\AuthService\AuthService::class, [
            '@request'
        ]
    ],
    'accessChecker' => [
        \Application\Service\AccessChecker\AccessChecker::class, [
            '@auth'
        ]
    ],
    'appender' => [
        \Application\Service\Appender\Appender::class, [
            '@session'
        ]
    ],
    'translator' => [
        \Application\Service\Translator\Translator::class, [
            '@request', [
                'adapter' => 'files',
                'path' => dirname(__DIR__). '/config/langs'
            ]
        ]
    ],
    'router' => [
        \Application\Service\Router\Router::class, [
            '@request',
            include 'routes.php'
        ]
    ],
    'commandRouter' => [
        \Application\Service\Router\Router::class, [
            '@request',
            include 'routes.php'
        ]
    ]
];