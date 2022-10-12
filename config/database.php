<?php

use Illuminate\Support\Str;

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

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => env('DATABASE_URL'),
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
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
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', 'localhost'),
            'port' => env('DB_PORT', '1433'),
            'database' => env('DB_DATABASE', 'forge'),
            'username' => env('DB_USERNAME', 'forge'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],
        'import' => [
            'driver' => 'fpdo',
            'charset' => 'utf8mb4',
            'prefix' => '',
            'prefix_indexes' => true,
            'tables' => [
                'prodotti' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Prodotti.xlsx')
                ],
                'abbonamenti' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Abbonamenti.xlsx')
                ],
                'prodotti_collegati' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Prodotti_Collegati.xlsx')
                ],
                'magazzino' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Magazzino.xlsx')
                ],
                'categoria1' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Categoria1.xlsx')
                ],
                'categoria2' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Categoria2.xlsx')
                ],
                'categoria3' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Categoria3.xlsx')
                ],
                'marche' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Marche.xlsx')
                ],
                'ordini' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Ordini.xlsx')
                ],
                'ordini_righe' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/OrdiniR.xlsx')
                ],
                'clienti' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Clienti.xlsx')
                ],
                'allegati' => [
                    'type' => 'xlsx',
                    'source' => storage_path('/dbImport/Allegati.xlsx')
                ],
                'import_products' =>[
                    'type'=>'csv',
                    'source' => storage_path('/import/generated/products.csv'),
                    'write' => true,
                    'schema' => [
                        'id'                => ['type' => 'integer'],
                        'code'              => ['type' => 'string'],
                        'name'              => ['type' => 'string','1000'],
                        'categories'        => ['type'=>'string'],
                        'description'       => ['type'=>['string','1000']],
                        'description_short' => ['type'=>['string','1000']],
                        'active'            => ['type' => 'boolean'],
                        'price'             => ['type' => ['float','00000.00000']],
                        'tags'              => ['type' => ['string','1000']],
                        'features'          => ['type' => ['string','1000']],
                        'images'            => ['type' => ['string','1000']]
                    ],
                    'options' => [
                        'use_header' => true,
                        'delimiter' => '|'
                    ]
                ],
                'import_combinations' =>[
                    'type'=>'csv',
                    'source' => storage_path('/import/generated/combinations.csv'),
                    'write' => true,
                    'schema' => [
                        'reference'     => ['type' => 'string'],
                        'code'          => ['type' => 'string'],
                        'isDefault'     => ['type' => 'boolean'],
                        'isbn'          => ['type' => 'string'],
                        'impact_price'  => ['type' => ['float','00000.00000']],
                        'impact_weight' => ['type' => ['float','00000.00000']],
                        'attributes'    => ['type' => 'string'],
                        'values'        => ['type' => 'string'],
                        'quantity'      => ['type' => ['integer','1000']],
                    ],
                    'options' => [
                        'use_header' => true,
                        'delimiter' => '|'
                    ]
                ],
                'import_isbn' => [
                    'type' => 'json',
                    'source' => storage_path('/import/generated/import_isbn.json'),
                    'write' => true,
                    'schema' => [
                        'type'      => ['type'=>'string'],
                        'reference' => ['type'=>'string'],
                        'code'      => ['type'=>'string']
                    ]
                ],
                'import_related_products' => [
                    'type' => 'json',
                    'source' => storage_path('/import/generated/import_related.json'),
                    'write' => true,
                    'schema' => [
                        'id_product'            => ['type'=>['integer','1000']],
                        'id_related_product'    => ['type'=>['integer','1000']],
                    ]
                ],
                'import_attachments' => [
                    'type' => 'json',
                    'source' => storage_path('/import/generated/import_attachments.json'),
                    'write' => true,
                    'schema' => [
                        'id_product'    => ['type'=>['integer','1000']],
                        'title'         => ['type'=>'string'],
                        'file'          => ['type'=>'string'],
                    ]
                ],
                'import_digital_content' => [
                    'type' => 'json',
                    'source' => storage_path('/import/generated/import_digital_content.json'),
                    'write' => true,
                    'schema' => [
                        'id_product'    => ['type'=>['integer','1000']],
                        'title'         => ['type'=>'string'],
                        'file'          => ['type'=>'string'],
                    ]
                ],

                'import_errors' => [
                    'type'      =>'json',
                    'source'    => storage_path('/import/generated/import_errors.json'),
                    'write'     => true,
                    'schema' => [
                        'date'         => ['type'=>['string',100]],
                        'operation'    => ['type'=>['string',100]],
                        'message'      => ['type'=>['string',1000]],
                    ]
                ],
                'import_special_prices' => [
                    'type' => 'json',
                    'source'    => storage_path('/import/generated/import_special_prices.json'),
                    'write'     => true,
                    'schema' => [
                        'price'             => ['type'=>['double','00000.00000']],
                        'combo_reference'   => ['type'=>['string',100]],
                    ]

                ],
                'import_customers' => [
                    'type'=>'csv',
                    'source' => storage_path('/import/generated/import_customers.csv'),
                    'write' => true,
                    'schema' => [
                        'id'                => ['type' => 'integer'],
                        'active'            => ['type' => 'boolean'],
                        'title_id'          => ['type' => 'integer'],
                        'first_name'        => ['type' => 'string'],
                        'last_name'         => ['type'=>'string'],
                        'email'             => ['type'=>'string'],
                        'username'          => ['type'=>'string'],
                        'password'          => ['type'=>'string'],
                        'newsletter'        => ['type' => 'boolean'],
                        'optin'             => ['type' => 'boolean'],
                        'registration_date' => ['type'=>'string'],
                        'groups'            => ['type'=>'string'],
                        'default_group'     => ['type' => 'integer'],
                        'idweb'             => ['type' => 'integer'],
                        'code'              => ['type'=>'string'],
                    ],
                    'options' => [
                        'use_header' => true,
                        'delimiter' => '|'
                    ]
                ],
                'import_customers_duplicated' => [
                    'type'=>'csv',
                    'source' => storage_path('/import/generated/import_customers_duplicated.csv'),
                    'write' => true,
                    'schema' => [
                        'id'                => ['type' => 'integer'],
                        'email'             => ['type' => 'string'],
                        'idweb'             => ['type' => 'integer'],
                        'code'              => ['type'=>'string'],
                        'duplicated_id'     => ['type' => 'integer'],
                        'duplicated_email'  => ['type' => 'string'],
                        'duplicated_idweb'  => ['type' => 'integer'],
                        'duplicated_code'   => ['type'=>'string'],
                    ],
                    'options' => [
                        'use_header' => true,
                        'delimiter' => '|'
                    ]
                ],
                'import_addresses' => [
                    'type'=>'csv',
                    'source'    => storage_path('/import/generated/import_addresses.csv'),
                    'write'     => true,
                    'schema'    => [
                        // 'id'                => ['type' => 'integer'],
                        'alias'             => ['type'=>'string'],
                        'active'            => ['type' => 'boolean'],
                        'email'             => ['type'=>'string'],
                        'customer_id'       => ['type'=>'integer'],
                        'company'           => ['type' => 'string'],
                        'first_name'        => ['type' => 'string'],
                        'last_name'         => ['type'=>'string'],
                        'address_1'         => ['type' => 'string'],
                        'address_2'         => ['type' => 'string'],
                        'zip'               => ['type' => 'string'],
                        'city'              => ['type' => 'string'],
                        'country'           => ['type' => 'string'],
                        'state'             => ['type' => 'string'],
                        'other'             => ['type' => 'string'],
                        'phone'             => ['type' => 'string'],
                        'mobile'            => ['type' => 'string'],
                        'vat_number'        => ['type' => 'string'],
                        'company'           => ['type' => 'string'],
                    ],
                    'options' => [
                        'use_header' => true,
                        'delimiter' => '|'
                    ]
                ],
            ],
        ]
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
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => env('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => env('REDIS_CLUSTER', 'redis'),
            'prefix' => env('REDIS_PREFIX', Str::slug(env('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => env('REDIS_URL'),
            'host' => env('REDIS_HOST', '127.0.0.1'),
            'password' => env('REDIS_PASSWORD', null),
            'port' => env('REDIS_PORT', '6379'),
            'database' => env('REDIS_CACHE_DB', '1'),
        ],

    ],

];
