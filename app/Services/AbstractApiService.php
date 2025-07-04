<?php
namespace App\Services;

use GuzzleHttp\Client;
use App\Services\Contracts\ApiServiceInterface;

abstract class AbstractApiService implements ApiServiceInterface
{
    protected $apiKey;
    protected $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }
} 