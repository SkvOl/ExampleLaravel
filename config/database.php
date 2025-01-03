<?php

use Illuminate\Support\Str;

if(isset($_SERVER['REQUEST_URI'])) $type = explode('/', $_SERVER['REQUEST_URI'])[1];
else $type = 'prod';


switch ($type){
    case 'prod':{
        $host = 'DB_HOST_PROD';
        $port = 'DB_PORT';
        $username = 'DB_USERNAME_PROD';
        $password = 'DB_PASSWORD_PROD';
        break;
    }
    case 'test':{
        $host = 'DB_HOST_TEST';
        $port = 'DB_PORT';
        $username = 'DB_USERNAME_TEST';
        $password = 'DB_PASSWORD_TEST';
        break;
    }
    case 'mdl':{
        $host = 'DB_HOST_MDL';
        $port = 'DB_PORT';
        $username = 'DB_USERNAME_MDL';
        $password = 'DB_PASSWORD_MDL';
        break;
    }
    case 'new':{
        $host = 'DB_HOST_NEW';
        $port = 'DB_PORT_NEW';
        $username = 'DB_USERNAME_NEW';
        $password = 'DB_PASSWORD_NEW';

        break;
    }
    default:{
        $host = 'DB_HOST_PROD';
        $port = 'DB_PORT';
        $username = 'DB_USERNAME_PROD';
        $password = 'DB_PASSWORD_PROD';
        break;
    }
}

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env($host, '127.0.0.1'),
            'port' => env($port, '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env($username, 'forge'),
            'password' => env($password, ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'modes'=> [
                // 'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                'STRICT_TRANS_TABLES',
                // 'NO_ZERO_IN_DATE',
                // 'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                // 'NO_AUTO_CREATE_USER', // This has been deprecated and will throw an error in mysql v8
                'NO_ENGINE_SUBSTITUTION',
            ],
        ],

        'pulse' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_TEST', '127.0.0.1'),
            'port' => env($port, '3306'),
            'database' => env('PULSE_DB_DATABASE'),
            'username' => env('DB_USERNAME_TEST'),
            'password' => env('DB_PASSWORD_TEST', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'modes'=> [
                // 'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                'STRICT_TRANS_TABLES',
                // 'NO_ZERO_IN_DATE',
                // 'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                // 'NO_AUTO_CREATE_USER', // This has been deprecated and will throw an error in mysql v8
                'NO_ENGINE_SUBSTITUTION',
            ],
        ],

        'telescope' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST_TEST', '127.0.0.1'),
            'port' => env($port, '3306'),
            'database' => env('TELESCOPE_DB_DATABASE'),
            'username' => env('DB_USERNAME_TEST'),
            'password' => env('DB_PASSWORD_TEST', ''),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => env('MYSQL_ATTR_SSL_CA'),
            ]) : [],
            'modes'=> [
                // 'ONLY_FULL_GROUP_BY', // Disable this to allow grouping by one column
                'STRICT_TRANS_TABLES',
                // 'NO_ZERO_IN_DATE',
                // 'NO_ZERO_DATE',
                'ERROR_FOR_DIVISION_BY_ZERO',
                // 'NO_AUTO_CREATE_USER', // This has been deprecated and will throw an error in mysql v8
                'NO_ENGINE_SUBSTITUTION',
            ],
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
