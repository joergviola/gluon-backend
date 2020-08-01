<?php

return [
    'auth' => [
        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => \Gluon\Backend\Api\User::class,
            ],
        ],
        'guard' => [
            'gluon' => [
                'driver' => 'passport',
                'provider' => 'users'
            ]
        ]
    ]
];