<?php

namespace Tests\Functional;

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Config;
use App\Helpers\HttpClient;
use PHPUnit\Framework\TestCase;

class ApiCrudTest extends TestCase
{
    private $pdo;

    private $queryBuilder;

    private $httpClient;

    public function setUp(): void
    {
        $this->pdo = new PDODatabaseConnection($this->getConfig());

        $this->queryBuilder = new PDOQueryBuilder($this->pdo->connect());

        $this->httpClient = new HttpClient();

        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function itCanCreateSingleRecord()
    {
        $data = [
            'json' => [
                'title' => 'dummy title',
                'text' => 'dummy text',
                'link' => 'http://dumy.fz',
                'user_id' => 1,
            ]
        ];

        $response = $this->httpClient->post('index.php', $data);

        $this->assertEquals(200, $response->getStatusCode());

        $bug = $this->queryBuilder
            ->table('bugs')
            ->where('title', $data['json']['title'])
            ->where('text', $data['json']['text'])
            ->first();

        $this->assertNotNull($bug);

        return $bug;
    }

    /**
     * @test
     * @depends itCanCreateSingleRecord
     */
    public function itCanUpdateSingleRecord($record)
    {
        $data = [
            'json' => [
                'id' => $record->id,
                'title' => 'new title',
                'text' => 'new text',
            ]
        ];

        $response = $this->httpClient->put('index.php', $data);

        $this->assertEquals(200, $response->getStatusCode());

        $newBug = $this->queryBuilder
            ->table('bugs')
            ->find($record->id);

        $this->assertNotNull($newBug);

        $this->assertEquals('new title', $newBug->title);
    }

    /**
     * @test
     * @depends itCanCreateSingleRecord
     */
    public function itCanFetchSingleRecord($record)
    {
        $data = [
            'json' => [
                'id' => $record->id,
            ]
        ];

        $response = $this->httpClient->get('index.php', $data);

        $this->assertEquals(200, $response->getStatusCode());
        
        $this->assertArrayHasKey('id',json_decode($response->getBody(),true));
        
    }


    /**
     * @test
     * @depends itCanCreateSingleRecord
     */
    public function itCanDeleteSingleRecord($record)
    {
        $data = [
            'json' => [
                'id' => $record->id,
            ]
        ];

        $response = $this->httpClient->delete('index.php', $data);

        $this->assertEquals(204, $response->getStatusCode());

        $bug = $this->queryBuilder
            ->table('bugs')
            ->find($record->id);

        $this->assertEmpty($bug);

    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
