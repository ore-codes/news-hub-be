<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class NytimesApiService
{
    protected $apiKey;
    protected $client;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
        $this->client = new Client();
    }

    public function fetchArticles()
    {
        $url = 'https://api.nytimes.com/svc/topstories/v2/home.json?api-key=' . $this->apiKey;
        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);
        $articles = [];
        foreach (Arr::get($data, 'results', []) as $item) {
            $image = null;
            if (!empty($item['multimedia']) && is_array($item['multimedia'])) {
                $image = $item['multimedia'][0]['url'] ?? null;
            }
            $articles[] = [
                'title' => $item['title'] ?? null,
                'content' => $item['abstract'] ?? null,
                'author' => $item['byline'] ?? null,
                'source' => 'nytimes',
                'category' => $item['section'] ?? 'Unknown',
                'published_at' => isset($item['published_date']) ? date('Y-m-d H:i:s', strtotime($item['published_date'])) : null,
                'source_id' => $item['uri'] ?? null,
                'url' => $item['url'] ?? null,
                'url_to_image' => $image,
                'raw_json' => json_encode($item),
            ];
        }
        return $articles;
    }
} 