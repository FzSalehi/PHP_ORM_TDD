<?php

namespace Tests\unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Exceptions\PdoDatabaseConnectionException;
use App\Helpers\Config;
use PDO;
use PHPUnit\Framework\TestCase;

class PDODatabaseConnectionTest extends TestCase
{
    /**
     * @test
     */
    public function PDODatabaseConnectionClassShouldImplemetsDatabaseConnnectionInterface()
    {
        $config = $this->getConfig();

        $pdodatabaseconnection = new PDODatabaseConnection($config);

        $this->assertInstanceOf(DatabaseConnectionInterface::class,$pdodatabaseconnection);
    }
    /**
     * @test
     */
    public function connectMethodShouldConnectToDB()
    {
        $pdo = new PDODatabaseConnection($this->getConfig());
        
        $pdo->connect();

        $this->assertInstanceOf(PDO::class,$pdo->getConnection());
    }

    /**
     * @test
     */

     public function ifPdoConnectionFailsShouldThrowPdoDatabaseConnectionException()
     {
        $this->expectException(PdoDatabaseConnectionException::class);
        
        $pdo = new PDODatabaseConnection($this->getWrongConfig());
        
        $pdo->connect();
     }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }

    private function getWrongConfig()
    {
        return Config::get('database' , 'pdo_invalid');
    }
}
