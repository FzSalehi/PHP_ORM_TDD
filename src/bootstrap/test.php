<?php

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;

require __DIR__."/../../vendor/autoload.php";

$config = Config::get('database','pdo_testing');

$pdo = (new PDODatabaseConnection($config));

$queryBuilder = new PDOQueryBuilder($pdo->connect());

$queryBuilder->truncateAllTables();



