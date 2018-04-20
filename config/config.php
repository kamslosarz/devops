<?php

return [
    'routes' => [
        '/admin/index' => [
            'Admin\AdminController',
            'IndexAction',
        ],
        '/admin/project/[id]' => [
            'Admin\AdminController',
            'ProjectAction',
        ],
        '/test/test' => [
            'IndexController',
            'IndexAction',
        ]
    ],
    'logger' => [
        'ApplicationLogger' => [
            'dir' => dirname(__DIR__) . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR
        ]
    ],
    'database' => [
        'host' => 'localhost',
        'database' => 'devops',
        'user' => 'devops',
        'password' => 'aTRVxqw76nVqa'
    ]
];