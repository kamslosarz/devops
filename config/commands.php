<?php

return [
    'admin:create' => [
        [
            \Application\Console\Command\CommandValidator::class, 'validate',
            [
                ['username', \Application\ParameterHolder\Constraint\UsernameMatcher::class],
                ['password', \Application\ParameterHolder\Constraint\PasswordMatcher::class],
                ['force', \Application\ParameterHolder\Constraint\Boolean::class, true]
            ]
        ],
        [
            \Application\Console\Command\Command\Admin\Create::class, 'execute'
        ],
    ],
    'cache:build' => [
        [\Application\Console\Command\Command\Cache\Build::class, 'execute']
    ],
    'cache:clear' => [
        [\Application\Console\Command\Command\Cache\Clear::class, 'execute']
    ],
    'docker:build' => [
        [\Application\Console\Command\Command\Docker\Build::class, 'execute']
    ],
    'docker:ssh' => [
        [
            \Application\Console\Command\CommandValidator::class, 'validate',
            [
                ['username', \Application\ParameterHolder\Constraint\UsernameMatcher::class, true]
            ]
        ],
        [
            \Application\Console\Command\Command\Docker\Ssh::class, 'execute'
        ]
    ],
    'docker:start' => [
        [\Application\Console\Command\Command\Docker\Start::class, 'execute']
    ],
    'docker:stop' => [
        [\Application\Console\Command\Command\Docker\Stop::class, 'execute']
    ],
];