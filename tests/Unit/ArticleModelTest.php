<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ArticleModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_article_can_be_created()
    {
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'Test content',
            'author' => 'Test Author',
            'source' => 'newsapi',
            'category' => 'Technology',
            'published_at' => '2024-01-01 10:00:00',
            'source_id' => 'test-1',
            'url' => 'https://example.com',
            'url_to_image' => 'https://example.com/image.jpg',
        ]);

        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'source' => 'newsapi',
        ]);
    }

    public function test_article_searchable_array_contains_correct_fields()
    {
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'Test content',
            'author' => 'Test Author',
            'source' => 'newsapi',
            'category' => 'Technology',
            'published_at' => '2024-01-01 10:00:00',
            'source_id' => 'test-1',
            'url' => 'https://example.com',
            'url_to_image' => 'https://example.com/image.jpg',
        ]);

        $searchableArray = $article->toSearchableArray();

        $this->assertArrayHasKey('id', $searchableArray);
        $this->assertArrayHasKey('title', $searchableArray);
        $this->assertArrayHasKey('content', $searchableArray);
        $this->assertArrayHasKey('author', $searchableArray);
        $this->assertArrayHasKey('source', $searchableArray);
        $this->assertArrayHasKey('category', $searchableArray);
        $this->assertArrayHasKey('published_at', $searchableArray);
        $this->assertArrayHasKey('url', $searchableArray);
        $this->assertArrayHasKey('url_to_image', $searchableArray);

        $this->assertEquals('Test Article', $searchableArray['title']);
        $this->assertEquals('Test content', $searchableArray['content']);
        $this->assertEquals('Test Author', $searchableArray['author']);
    }

    public function test_article_fillable_fields_are_set_correctly()
    {
        $fillableFields = [
            'title',
            'content',
            'author',
            'source',
            'category',
            'published_at',
            'source_id',
            'url',
            'url_to_image',
            'raw_json',
        ];

        $this->assertEquals($fillableFields, (new Article())->getFillable());
    }

    public function test_article_can_be_updated()
    {
        $article = Article::create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'author' => 'Original Author',
            'source' => 'newsapi',
            'category' => 'Technology',
            'published_at' => '2024-01-01 10:00:00',
            'source_id' => 'test-1',
            'url' => 'https://example.com',
            'url_to_image' => 'https://example.com/image.jpg',
        ]);

        $article->update([
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);

        $this->assertDatabaseHas('articles', [
            'id' => $article->id,
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ]);
    }

    public function test_article_can_be_deleted()
    {
        $article = Article::create([
            'title' => 'Test Article',
            'content' => 'Test content',
            'author' => 'Test Author',
            'source' => 'newsapi',
            'category' => 'Technology',
            'published_at' => '2024-01-01 10:00:00',
            'source_id' => 'test-1',
            'url' => 'https://example.com',
            'url_to_image' => 'https://example.com/image.jpg',
        ]);

        $articleId = $article->id;
        $article->delete();

        $this->assertDatabaseMissing('articles', [
            'id' => $articleId,
        ]);
    }
} 