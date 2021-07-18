<?php

namespace Tests\unit;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class PDOQueryBuilderTest extends TestCase
{
    // Testing for CRUD :
    private $pdo;

    private $queryBuilder;

    public function setUp(): void
    {
        $this->pdo = new PDODatabaseConnection($this->getConfig());

        $this->pdo->connect();

        $this->queryBuilder = new PDOQueryBuilder($this->pdo);

        parent::setUp();
    }

    public function tearDown(): void
    {
        $this->queryBuilder->truncateAllTables();

        parent::tearDown();
    }

    /**
     * @test
     */
    public function itCanCreateData()
    {
        // we are assuming that user wants to report a bug for a linked page
        $result = $this->insertIntoDB();

        $this->assertIsInt($result);

        $this->assertGreaterThan(0, $result);

    }

    /**
     * @test
     */
    public function itCanUpdateExistedData()
    {
        $id = $this->insertIntoDB();

        $data = [
            'text' => 'some other text',
            'link' => 'http://link.fz2',
        ];

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('id', $id)
            ->where('text', 'some text')
            ->update($data);

        $this->assertEquals(1, $result);
    }

    private function insertIntoDB()
    {
        $data = [
            'title' => 'bug title',
            'text' => 'some text',
            'link' =>  'http://link.fz',
            'user_id' => rand(2, 10),
        ];

        return $this->queryBuilder->table('bugs')->create($data);
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
