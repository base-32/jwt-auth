<?php

return [
    /*
    |--------------------------------------------------------------------------
    | JWT Route Prefix
    |--------------------------------------------------------------------------
    |
    | This is the prefix of the routes that will be registered with the CarterParker\JWTAuth package.
    | By default it is prefixed with auth so the endpoints are as follows
    |
    | - /auth/login
    | - /auth/forgot-password
    | - /auth/reset-password
    | - /auth/verify-token
    */

    'prefix' => 'auth',

    /*
    |--------------------------------------------------------------------------
    | Current User
    |--------------------------------------------------------------------------
    |
    | This is the route in order to get the details of the current user which is logged in.
    | You can also specify what columns to bring back with the user.
    */

    'current_user' => [
        'route' => 'user',
        'attributes' => [
            'first_name', 'last_name'
        ]
    ]
];
