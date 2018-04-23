<?php

return [
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
            '@session'
        ]
    ],
    'entityManager' => [
        \Application\Service\Orm\Orm::class, [

        ]
    ],
    'authService' => [
        \Application\Service\AuthService\AuthService::class, [
            '@request',
            '@entityManager'
        ]
    ]
];