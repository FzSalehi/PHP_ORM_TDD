<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;

class PDOQueryBuilder
{
    protected $pdo;

    protected $table;

    public function __construct(DatabaseConnectionInterface $pdoconnection)
    {
        $this->pdo = $pdoconnection->getConnection();
    }

    public function table(string $table)
    {
        $this->table = $table;

        return $this;
    }

    public function create(array $data)
    {
        $fields = implode(',', array_keys($data));

        $placeholder = [];

        foreach ($data as $column => $value) {
            $placeholder[] = '?';
        }

        $placeholder = implode(',', $placeholder);

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholder})";

        $query = $this->pdo->prepare($sql);

        $query->execute(array_values($data));
       
        return (int) $this->pdo->lastInsertId();
    }
}
