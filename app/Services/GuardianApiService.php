<?php
namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Arr;

class GuardianApiService
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
        // Fetch total pages first
        $url = 'https://content.guardianapis.com/search?show-fields=headline,byline,bodyText,thumbnail&api-key=' . $this->apiKey . '&page-size=1';
        $response = $this->client->get($url);
        $data = json_decode($response->getBody(), true);
        $totalPages = Arr::get($data, 'response.pages', 1);
        $maxPages = min($totalPages, 100);

        // Pick 3 unique random pages
        $pages = [];
        while (count($pages) < 3) {
            $rand = random_int(1, $maxPages);
            if (!in_array($rand, $pages)) {
                $pages[] = $rand;
            }
        }

        $articles = [];
        foreach ($pages as $page) {
            $url = 'https://content.guardianapis.com/search?show-fields=headline,byline,bodyText,thumbnail&api-key=' . $this->apiKey . '&page-size=100&page=' . $page;
            $response = $this->client->get($url);
            $data = json_decode($response->getBody(), true);
            $results = Arr::get($data, 'response.results', []);
            foreach ($results as $item) {
                $fields = $item['fields'] ?? [];
                $articles[] = [
                    'title' => $fields['headline'] ?? $item['webTitle'] ?? null,
                    'content' => $fields['bodyText'] ?? null,
                    'author' => $fields['byline'] ?? null,
                    'source' => 'guardian',
                    'category' => $item['sectionName'] ?? 'Unknown',
                    'published_at' => isset($item['webPublicationDate']) ? date('Y-m-d H:i:s', strtotime($item['webPublicationDate'])) : null,
                    'source_id' => $item['id'] ?? null,
                    'url' => $item['webUrl'] ?? null,
                    'url_to_image' => $fields['thumbnail'] ?? null,
                    'raw_json' => json_encode($item),
                ];
            }
        }
        return $articles;
    }
}
