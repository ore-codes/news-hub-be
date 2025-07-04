<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class NewsApiService extends AbstractApiService
{
    public function fetchArticles()
    {
        $url = 'https://newsapi.org/v2/top-headlines?language=en&pageSize=100&apiKey=' . $this->apiKey;
        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);
        $articles = [];
        foreach (Arr::get($data, 'articles', []) as $item) {
            $articles[] = [
                'title' => $item['title'] ?? null,
                'content' => $item['content'] ?? null,
                'author' => $item['author'] ?? null,
                'source' => 'newsapi',
                'category' => 'Unknown',
                'published_at' => isset($item['publishedAt']) ? date('Y-m-d H:i:s', strtotime($item['publishedAt'])) : null,
                'source_id' => $item['url'] ?? null,
                'url' => $item['url'] ?? null,
                'url_to_image' => $item['urlToImage'] ?? null,
                'raw_json' => json_encode($item),
            ];
        }
        return $articles;
    }
} 