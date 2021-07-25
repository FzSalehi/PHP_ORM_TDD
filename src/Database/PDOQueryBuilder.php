<?php

namespace App\Database;

use App\Contracts\DatabaseConnectionInterface;
use PDO;
use PhpParser\Node\Stmt\Continue_;

class PDOQueryBuilder
{
    protected $pdo;

    protected $table;

    protected $columns;

    protected $conditions;

    protected $values;

    protected $statment;

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

        $this->values = array_values($data);

        $this->execute($sql);

        return (int) $this->pdo->lastInsertId();
    }

    public function where(string $column, string $value)
    {
        if(is_null($this->conditions)){
            
            $this->conditions = "{$column} = ?";

        }else{

            $this->conditions .= " AND {$column} = ?";
        }
        
        $this->values[] = $value;

        return $this;
    }

    public function update(array $data)
    {
        $fields = [];

        foreach ($data as $column => $value) {
            $fields[] = "`{$column}` = '{$value}'";
        }

        $fields = implode(' , ',$fields);
        
        $sql = "UPDATE {$this->table} SET {$fields} WHERE {$this->conditions}";

        $this->execute($sql);

        return $this->statment->rowCount();
    }


    public function delete()
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->conditions}";

        $this->execute($sql);

        return $this->statment->rowCount();
    }

    public function get(array $columns = ['*'])
    {   
        $this->setColumns($columns);

        $sql = "SELECT {$this->columns} FROM {$this->table} WHERE {$this->conditions}";

        $this->execute($sql);

        return $this->statment->fetchAll();
    }

    public function first(array $columns = ['*'])
    {
        $data = $this->get($columns);

        return empty($data) ? null : $data[0];
    }

    public function find(int $id)
    {
        $result = $this->where('id', $id)->first();

        return ($result == null) ? [] : $result;
    }

    public function findBy(string $column, $value)
    {
        return $this->where($column, $value)->first();
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

    private function execute(string $sql)
    {
        $this->statment = $this->pdo->prepare($sql);

        $this->statment->execute($this->values);

        $this->values = [];

        $this->columns = [];

        return $this;
    }

    private function setColumns(array $columns)
    {
        foreach($columns as $column){

            if($column == '*') continue;

            $columns[] = '`'.$column.'`';

        }

        $columns = implode(',' , $columns);

        $this->columns = $columns;
    }
}
