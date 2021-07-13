<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use App\Exceptions\PdoDatabaseConnectionException;
use PDO;
use PDOException;

class PDODatabaseConnection implements DatabaseConnectionInterface
{
    protected $config;

    protected $connection;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function connect()
    {
        try {

            $this->connection = new PDO(...$this->generateDsn($this->config));

            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);

        } catch (PDOException $e) {

            throw new PdoDatabaseConnectionException($e->getMessage());
        }

        return $this;
    }

    public function getConnection()
    {

        return $this->connection;

    }

    private function generateDsn($config)
    {
        $dsn = "{$config['driver']}:host={$config['host']};dbname={$config['database']}";

        return [
            $dsn,
            $config['db_user'],
            $config['db_password']
        ];
    }
}
