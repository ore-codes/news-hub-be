<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Services\NewsApiService;
use App\Services\GuardianApiService;
use App\Services\NytimesApiService;
use App\Services\EventRegistryApiService;
use Illuminate\Support\Facades\Log;

class FetchNewsArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-news-articles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $newsApiKey = config('services.newsapi.key') ?? env('NEWSAPI_KEY');
        $guardianApiKey = config('services.guardian.key') ?? env('GUARDIAN_KEY');
        $nytApiKey = config('services.nytimes.key') ?? env('NYTIMES_KEY');
        $eventRegistryKey = config('services.eventregistry.key') ?? env('EVENTREGISTRY_KEY');

        $sources = [
            'newsapi' => new NewsApiService($newsApiKey),
            'guardian' => new GuardianApiService($guardianApiKey),
            'nytimes' => new NytimesApiService($nytApiKey),
            'eventregistry' => new EventRegistryApiService($eventRegistryKey),
        ];

        foreach ($sources as $source => $service) {
            $articles = $source === 'eventregistry' ? $service->fetchArticles(10) : $service->fetchArticles();
            $newCount = 0;
            foreach ($articles as $articleData) {
                $exists = Article::where('source', $articleData['source'])
                    ->where('source_id', $articleData['source_id'])
                    ->exists();
                if (!$exists) {
                    Article::create($articleData);
                    $newCount++;
                }
            }
            $this->info("$source: $newCount new articles saved.");
        }
    }
}
