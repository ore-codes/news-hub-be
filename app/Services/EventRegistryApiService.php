<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class EventRegistryApiService extends AbstractApiService
{
    public function fetchArticles($articlesCount = 10, $dateStart = null)
    {
        $url = 'https://eventregistry.org/api/v1/article/getArticles';
        $body = [
            'apiKey' => $this->apiKey,
            'lang' => 'eng',
            'dateStart' => $dateStart ?? date('Y-m-d', strtotime('-7 days')),
            'articlesCount' => $articlesCount,
            'articlesSortBy' => 'date',
            'includeArticleCategories' => true,
            'dataType' => 'news',
            'articleBodyLen' => -1,
        ];
        $response = $this->client->post($url, [
            'json' => $body
        ]);
        $data = json_decode($response->getBody(), true);
        $articles = [];
        foreach (Arr::get($data, 'articles.results', []) as $item) {
            $category = 'Unknown';
            if (!empty($item['categories']) && is_array($item['categories'])) {
                $category = $item['categories'][0]['label'] ?? 'Unknown';
            }
            $articles[] = [
                'title' => $item['title'] ?? null,
                'content' => $item['body'] ?? null,
                'author' => null,
                'source' => $item['source']['title'] ?? 'eventregistry',
                'category' => $category,
                'published_at' => isset($item['dateTimePub']) ? date('Y-m-d H:i:s', strtotime($item['dateTimePub'])) : null,
                'source_id' => $item['uri'] ?? null,
                'url' => $item['url'] ?? null,
                'url_to_image' => $item['image'] ?? null,
                'raw_json' => json_encode($item),
            ];
        }
        return $articles;
    }
} 