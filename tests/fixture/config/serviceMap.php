<?php

return [
    'config' => [
        \Application\Service\Config\Config::class, [
            include FIXTURE_DIR . '/config/configTest.php'
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
                'path' => FIXTURE_DIR . '/langs'
            ]
        ]
    ],
    'testService' => [
        \Test\Decorator\ServiceDecorator::class, [
            '@request', [
                'test' => 123
            ]
        ]
    ],
    'router' => [
        \Application\Service\Router\Router::class, [
            '@request',
            'routes' => include FIXTURE_DIR . '/config/routes.php'
        ]
    ],
    'commandRouter' => [
        \Application\Service\Router\Router::class, [
            '@request',
            'routes' => include FIXTURE_DIR . '/config/commands.php'
        ]
    ]
];