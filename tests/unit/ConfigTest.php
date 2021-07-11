<?php

namespace Tests\unit;

use App\Helpers\Config;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /**
     * @test
     */
    public function getFileContentsMethodReturnsArray()
    {
        $filecontent = Config::getFileContents('database');

        $this->assertIsArray($filecontent);

        return $filecontent;
    }
    /**
     * @test
     */
    public function getFileContentMethodReturnsExceptionIfFileDosenotExists()
    {
        $this->expectException('App\Exceptions\ConfigFileNotFoundExeption');

        $filecontent = Config::getFileContents('foo');
    }
    /**
     * @test
     * @depends getFileContentsMethodReturnsArray
     */
    public function getFileContentShouldReturnExpectedArray(array $filecontent)
    {
        $expecteddata = [
            'pdo' => [
                'driver' => 'mysql',
                'host' => '127.0.0.1',
                'database' => 'php_tdd_orm',
                'db_user' => 'root',
                'db_password' => '123456',
            ]
        ];

        $this->assertEquals($expecteddata,$filecontent);
    }

    /**
     * todo: fix this test
     */
    public function getMethodReturnsArray()
    {
        $pdoconfig = Config::get('database', 'pdo');
        
        var_dump($pdoconfig);

        $this->assertIsArray($pdoconfig);
    }
}
