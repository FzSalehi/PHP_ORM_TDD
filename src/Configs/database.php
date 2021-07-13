<?php

namespace App\Configs;

return [
    'pdo' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'php_tdd_orm',
        'db_user' => 'root',
        'db_password' => '',
    ],
    'pdo_testing' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'php_tdd_orm_testing',
        'db_user' => 'root',
        'db_password' => '',
    ],
    'pdo_invalid' =>[
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'foo_database',
        'db_user' => 'roo00t',
        'db_password' => '2222',
    ]
];