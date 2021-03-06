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

        $this->queryBuilder->beginTransaction();

        parent::setUp();
    }

    public function tearDown(): void
    {
        //$this->queryBuilder->truncateAllTables();

        $this->queryBuilder->rollBack();

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
        // create two records
        $firstrecordid = $this->insertIntoDB();
        $this->insertIntoDB([
            'text' => 'second text'
        ]);

        // requiested updating data
        $data = [
            'text' => 'some other text',
            'link' => 'http://link.fz2',
        ];

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('id', $firstrecordid)
            ->where('text', 'some text')
            ->update($data);

        $this->assertEquals(1, $result);
    }

    /**
     * @test
     */
    public function itCanUpdateExistedDataWithMultiConditions()
    {
        $this->multiInsertintoDB(4, ['title' => 'same text']);
        $this->insertIntoDB([
            'title' => 'same text',
            'link' => 'http://new.link.fz'
        ]);

        $result = $this->queryBuilder
            ->where('title', 'same text')
            ->where('link', 'http://new.link.fz')
            ->update(['title' => 'updated']);

        $this->assertEquals(1, $result);
    }

    /**
     * @test
     */
    public function itCanDeleteRecords()
    {
        // create 4 records
        $this->multiInsertintoDB(4);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'bug title')
            ->delete();

        $this->assertEquals(4, $result);
    }

    /**
     * @test
     */
    public function itCanFetchData()
    {
        $this->multiInsertintoDB(4);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'bug title')
            ->get();

        $this->assertEquals(4, count($result));
    }

    /**
     * @test
     */
    public function itCanFetchDataWithMultiConditions()
    {
        $this->multiInsertintoDB(2,['title' => 'same title']);
        $this->multiInsertintoDB(2,[
            'title' => 'same title',
            'link' => 'http://other.link.fz',
        ]);


        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'same title')
            ->where('link', 'http://other.link.fz')
            ->get();

        $this->assertEquals(2, count($result));
    }

    /**
     * @test
     */
    public function itCanFetchDesireColumns()
    {
        $this->multiInsertintoDB(4);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'bug title')
            ->get(['title', 'link']);

        $this->assertIsArray($result);

        $this->assertObjectHasAttribute('title', $result[0]);
        $this->assertObjectHasAttribute('link', $result[0]);

        $result = json_decode(json_encode($result[0]), true);

        $this->assertEquals(['title', 'link'], array_keys($result));
    }

    /**
     * @test
     */
    public function itCanFetchFirstRecord()
    {
        $this->multiInsertintoDB(4);

        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'bug title')
            ->first();

        $this->assertIsObject($result);

        $this->assertObjectHasAttribute('title', $result);

        $this->assertObjectHasAttribute('text', $result);

        $this->assertObjectHasAttribute('link', $result);

        $this->assertObjectHasAttribute('user_id', $result);
    }

    /**
     * @test
     */
    public function itCanFindById()
    {
        $this->insertIntoDB();
        $id = $this->insertIntoDB(['title' => 'find by id']);
        
        $result = $this->queryBuilder
            ->table('bugs')
            ->find($id);

        $this->assertIsObject($result);
        $this->assertEquals('find by id', $result->title);
    }

    /**
     * @test
     */
    public function itCanFindByAnyAttribute()
    {
        $this->insertIntoDB();
        $this->insertIntoDB(['title' => 'find by attr']);

        $result = $this->queryBuilder
            ->table('bugs')
            ->findBy('title', 'find by attr');

        $this->assertIsObject($result);

        $this->assertEquals('find by attr', $result->title);
    }
    /**
     * @test
     */
    public function findMethodWillReturnEmptyArrayIfRecordDidntFound()
    {
        $this->multiInsertintoDB(4);
        $result = $this->queryBuilder
            ->table('bugs')
            ->find(1000);

        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function getMethodWillReturnEmptyArrayIfRecordDidntFound()
    {
        $this->multiInsertintoDB(4);
        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'dummy')
            ->get();

        $this->assertEquals([], $result);
    }

    /**
     * @test
     */
    public function firstMethodWillReturnNullIfRecordDidntFound()
    {
        $this->multiInsertintoDB(4);
        $result = $this->queryBuilder
            ->table('bugs')
            ->where('title', 'dummy')
            ->first();

        $this->assertEquals(null, $result);
    }

    public function beginTransaction()
    {
        $this->pdo->beginTransaction();
    }

    public function rollBack()
    {
        $this->pdo->rollBack();
    }

    public function multiInsertintoDB(int $count, array $options = [])
    {
        for ($i = 1; $i <= $count; $i++) {
            $this->insertIntoDB($options);
        }
    }

    private function insertIntoDB(array $options = [])
    {

        $data = array_merge([
            'title' => 'bug title',
            'text' => 'some text',
            'link' =>  'http://link.fz',
            'user_id' => rand(2, 10),
        ], $options);

        return $this->queryBuilder->table('bugs')->create($data);
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
