<?php

namespace App\Configs;

return [
    'pdo' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'php_tdd_orm',
        'db_user' => 'root',
        'db_password' => '123456',
    ],
    'pdo_testing' => [
        'driver' => 'mysql',
        'host' => '127.0.0.1',
        'database' => 'php_tdd_orm_testing',
        'db_user' => 'root',
        'db_password' => '123456',
    ],
];