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
    'authService' => [
        \Application\Service\AuthService\AuthService::class, [
            '@request'
        ]
    ]
];