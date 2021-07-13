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

        Config::getFileContents('foo');
    }

    /**
     * @test
     */
    public function getMethodReturnsValidData()
    {
        $config = Config::get('database', 'pdo');

        $expecteddata =  [
            'driver' => 'mysql',
            'host' => '127.0.0.1',
            'database' => 'php_tdd_orm',
            'db_user' => 'root',
            'db_password' => '',
        ];

        $this->assertEquals($expecteddata, $config);
    }

}
