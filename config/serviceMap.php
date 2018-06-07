<?php

return [
    'logger' => [
        \Application\Service\Logger\Logger::class, [
            'instances' => [
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
            '@request',
            '@auth'
        ]
    ],
    'appender' => [
        \Application\Service\Appender\Appender::class, [
            '@session'
        ]
    ]
];