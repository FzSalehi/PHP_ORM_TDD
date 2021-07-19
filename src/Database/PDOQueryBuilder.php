<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use PDO;

class PDOQueryBuilder
{
    protected $pdo;

    protected $table;

    protected $conditions;

    protected $values;

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

    public function where(string $column, string $value)
    {
        $this->conditions[] = "{$column} = ?";

        $this->values[] = $value;

        return $this;
    }

    public function update(array $data)
    {
        $fields = [];

        foreach ($data as $column => $value) {
            $fields[] = "{$column} = '{$value}'";
        }

        $fields = implode(',', $fields);

        $conditions = implode(' and ', $this->conditions);

        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$conditions}";

        $query = $this->pdo->prepare($sql);

        $query->execute($this->values);

        return $query->rowCount();
    }


    public function delete()
    {
        $conditions = implode(' and ', $this->conditions);

        $sql = "DELETE FROM {$this->table} WHERE {$conditions}";
        
        $query = $this->pdo->prepare($sql);

        $query->execute($this->values);

        return $query->rowCount();
    }
    

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }

    public function truncateAllTables()
    {
        
        $query = $this->pdo->prepare('SHOW TABLES');

        $query->execute();

        foreach ($query->fetchAll(PDO::FETCH_COLUMN) as $table) {

            $this->pdo->prepare("truncate `{$table}`")->execute();
        }
    }
}
