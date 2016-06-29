<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_CLASS,

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

        'sqlite' => [
            'driver'   => 'sqlite',
            'database' => storage_path('database.sqlite'),
            'prefix'   => '',
        ],

        'mysql' => [
            'driver'    => 'mysql',
            'host'      => env('DB_HOST', '10.30.10.22'),
            'database'  => env('DB_DATABASE', 'iaserver'),
            'username'  => env('DB_USERNAME', 'root'),
            'password'  => env('DB_PASSWORD', 'apisql'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'iaserver' => [
            'driver'    => 'mysql',
            'host'      => env('IA_HOST', '10.30.10.22'),
            'database'  => env('IA_DATABASE', 'aoidata'),
            'username'  => env('IA_USERNAME', 'root'),
            'password'  => env('IA_PASSWORD', 'apisql'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'calidad' => [
            'driver'    => 'mysql',
            'host'      => env('IA_HOST', 'ARUS3DB19'),
            'database'  => env('IA_DATABASE', ''),
            'username'  => env('IA_USERNAME', ''),
            'password'  => env('IA_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'pizarra' => [
            'driver'    => 'sqlsrv',
            'host'      => env('PIZARRA_HOST', '10.30.10.94'),
            'database'  => env('PIZARRA_DATABASE', 'pizarra'),
            'username'  => env('PIZARRA_USERNAME', 'pizarra'),
            'password'  => env('PIZARRA_PASSWORD', 'Manzana2012'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'iaserver_prod' => [
            'driver'    => 'mysql',
            'host'      => env('IADEV_HOST', 'ARUSHDE04'),
            'database'  => env('IADEV_DATABASE', 'aoidata'),
            'username'  => env('IADEV_USERNAME', 'root'),
            'password'  => env('IADEV_PASSWORD', 'apisql'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'm77' => [
            'driver'    => 'mysql',
            'host'      => env('M77_HOST', '10.30.51.150'),
            'database'  => env('M77_DATABASE', 'aoidata_backdata'),
            'username'  => env('M77_USERNAME', 'root'),
            'password'  => env('M77_PASSWORD', ''),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'proevo' => [
            'driver'    => 'mysql',
            'host'      => env('PRO_HOST', '10.30.51.51'),
            'database'  => env('PRO_DATABASE', 'proevo'),
            'username'  => env('PRO_USERNAME', 'proevo'),
            'password'  => env('PRO_PASSWORD', 'apisql'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'traza' => [
            'driver'   => 'sqlsrv',
            'host'     => env('TRAZA_HOST', 'arus3db13'),
            'database' => env('TRAZA_DATABASE', 'Traza_material'),
            'username' => env('TRAZA_USERNAME', 'traza_mem'),
            'password' => env('TRAZA_PASSWORD', '82HBNYT9Y3'),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

        'sfcs' => [
            'driver'   => 'sqlsrv',
            'host'     => env('SFCS_HOST', 'arus3db13'),
            'database' => env('SFCS_DATABASE', 'Traza_material'),
            'username' => env('SFCS_USERNAME', 'sfcs'),
            'password' => env('SFCS_PASSWORD', 'sfcs-user-110927'),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

        'sfcs_aoi' => [
            'driver'   => 'sqlsrv',
            'host'     => env('SFCSAOI_HOST', 'arus3db13'),
            'database' => env('SFCSAOI_DATABASE', 'TRAZA_AOI'),
            'username' => env('SFCSAOI_USERNAME', 'trazaAOI'),
            'password' => env('SFCSAOI_PASSWORD', 'TrazaAOI2015'),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

        'traza_dev' => [
            'driver'   => 'sqlsrv',
            'host'     => env('TRAZADEV_HOST', 'ARUS3DB16'),
            'database' => env('TRAZADEV_DATABASE', 'Traza_material'),
            'username' => env('TRAZADEV_USERNAME', 'traza_mem'),
            'password' => env('TRAZADEV_PASSWORD', 'Passw0rd'),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

        'cogiscan_dev' => [
            'driver'   => 'sqlsrv',
            'host'     => env('COGISCANDEV_HOST', 'ARUS3DB16'),
            'database' => env('COGISCANDEV_DATABASE', 'Traza_material'),
            'username' => env('COGISCANDEV_USERNAME', 's-cogiscan'),
            'password' => env('COGISCANDEV_PASSWORD', 'Passw0rd'),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

        'amr_prod' => [
            'driver'    => 'mysql',
            'host'      => env('AMR_HOST', '10.30.10.97'),
            'database'  => env('AMR_DATABASE', 'cgs_interface_db'),
            'username'  => env('AMR_USERNAME', 'jmc'),
            'password'  => env('AMR_PASSWORD', 'apisql'),
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
            'strict'    => false,
        ],

        'cgs_prod' => [
            'driver'   => 'sqlsrv',
            'host'     => env('CGSDEV_HOST', 'ARUSHDB20'),
            'database' => env('CGSDEV_DATABASE', 'Traza_material'),
            'username' => env('CGSDEV_USERNAME', 's-cogiscanAMR'),
            'password' => env('CGSDEV_PASSWORD', 'hBN38@po'),
            'charset'  => 'utf8',
            'prefix'   => '',
        ],

        'pgsql' => [
            'driver'   => 'pgsql',
            'host'     => env('DB_HOST', 'localhost'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset'  => 'utf8',
            'prefix'   => '',
            'schema'   => 'public',
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

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer set of commands than a typical key-value systems
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'cluster' => false,

        'default' => [
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'database' => 0,
        ],

    ],

];