<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'admin' => [
            'users' => 'c,r,u,d',
            'payments' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'crew' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'logistic' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'purchasing' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'admin purchasing' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'admin operational' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'pic site' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'pic admin' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'pic incident' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
        'asuransi' => [
            'users' => 'c,r,u,d',
            'profile' => 'r,u'
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete'
    ]
];
