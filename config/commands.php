<?php

return [
    'admin:create' => [
        [
            \Application\Console\Command\CommandValidator::class, 'validate',
            [
                'username' => [
                    \Application\ParameterHolder\ConstraintValidator\Constraint\UsernameConstraint::class => [
                        'maxLength' => 8,
                        'minLength' => 5
                    ]
                ],
                'password' => [
                    \Application\ParameterHolder\ConstraintValidator\Constraint\PasswordConstraint::class => [
                        'maxLength' => 12,
                        'minLength' => 6
                    ]
                ],
                'force' => [
                    \Application\ParameterHolder\ConstraintValidator\Constraint\Boolean::class => [
                        'optional' => true
                    ]
                ]
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
                'username' => [
                    \Application\ParameterHolder\ConstraintValidator\Constraint\UsernameConstraint::class => [
                        'maxLength' => 8,
                        'minLength' => 5
                    ],
                    \Application\ParameterHolder\ConstraintValidator\Constraint\LengthConstraint::class => [
                        'min' => 1,
                        'max' => 2
                    ]
                ],
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