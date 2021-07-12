<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;

class PDODatabaseConnection implements DatabaseConnectionInterface
{
    private $config;

    public function __construct(...$config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        
    }

    public function getConnection()
    {

    }
}