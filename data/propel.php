<?php
return [
    'propel' => [
        'paths' => [
//            'projectDir' => '',
//            'schemaDir' => '',
//            'outputDir' => '',
            'phpDir' => '../src',
            'phpConfDir' => '../config/propel',
            'sqlDir' => '../tests/fixture',
            'migrationDir' => 'migrations',
//            'composerDir' => ''
        ],
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


