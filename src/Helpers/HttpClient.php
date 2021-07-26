<?php

namespace App\Helpers;

use GuzzleHttp\Client;

class HttpClient extends Client
{
    
    public function __construct()
    {
        $config = Config::get('app')['base_uri'];

        parent::__construct(['base_uri' => $config]);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

}