<?php

require_once './vendor/autoload.php';

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;


$config = Config::get('database', 'pdo_testing');

$pdo = new PDODatabaseConnection($config);

$queryBuilder = new PDOQueryBuilder($pdo->connect());

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $queryBuilder->table('bugs')->create(request());

    json_response(null, 200);

}

function request()
{
    return json_decode(file_get_contents('php://input'), true);
}

function json_response($data = null, $statusCode = 200)
{
    header_remove();

    header('Content-type:application/json');

    http_response_code($statusCode);

    //echo json_encode($data);

    exit();
}