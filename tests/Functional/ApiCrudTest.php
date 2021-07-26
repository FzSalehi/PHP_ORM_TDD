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

        echo $response->getBody();
        
        $bug = $this->queryBuilder
            ->table('bugs')
            ->where('title', $data['json']['title'])
            ->where('text', $data['json']['text'])
            ->first();

        $this->assertNotNull($bug);
    }

    private function getConfig()
    {
        return Config::get('database', 'pdo_testing');
    }
}
