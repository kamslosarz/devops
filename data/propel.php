<?php
return [
    'propel' => [
        'database' => [
            'connections' => [
                'default' => [
                    'adapter' => 'sqlite',
                    'dsn' => sprintf('sqlite:%s/devops.db3', __DIR__),
                    'user' => 'root',
                    'password' => '',
                    'settings' => [
                        'charset' => 'utf8'
                    ]
                ]
            ]
        ]
    ]
];
