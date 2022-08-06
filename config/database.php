<?php

return [
    'default' => env('DB_CONNECTION', 'mysql'),
    'connections' => [
        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST'),
            'database' => env('DB_DATABASE'),
            'username' => env('DB_USERNAME'),
            'password' => env('DB_PASSWORD'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ],
        'mongodb' => [
            'driver' => env('DB_CONNECTION_SECOND'),
            'host' => env('DB_HOST_SECOND'),
            'port' => env('DB_PORT_SECOND'),
            'database' => env('DB_DATABASE_SECOND'),
            'username' => env('DB_USERNAME_SECOND'),
            'password' => env('DB_PASSWORD_SECOND'),
            'options' => [
                'database' =>  env('DB_DATABASE_SECOND')  // sets the authentication database required by mongo 3
            ]
        ],
        'sqlite' => [
            'driver' => 'sqlite',
            'database' => storage_path('database.sqlite'),
            // 'database' => database_path('database.sqlite'),
            // 'database' => ':memory:',
            // 'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],
    ],


    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',
];
