<?php

namespace Tests\unit;

use App\Contracts\DatabaseConnectionInterface;
use App\Database\PDODatabaseConnection;
use App\Helpers\Config;
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

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
