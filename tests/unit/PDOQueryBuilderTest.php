<?php

namespace Tests\unit;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    // Testing for CRUD :

    /**
     * @test
     */
    public function itCanCreateData()
    {
        $pdodatabaseconnection = new PDODatabaseConnection($this->getConfig());

        $pdodatabaseconnection->connect();
        
        $queryBuilder = new PDOQueryBuilder($pdodatabaseconnection);
        
        // we are assuming that user wants to report a bug for a linked page
        $data = [
            'title' => 'bug title',
            'text' => 'some text',
            'link' =>  'http://link.fz',
            'user_id' => 1,
        ];

        $result = $queryBuilder->table('bugs')->create($data);

        $this->assertIsInt($result);

        $this->assertGreaterThan(0,$result);
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}