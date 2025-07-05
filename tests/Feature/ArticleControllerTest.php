<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Article;
use App\Models\User;
use App\Models\UserPreference;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Article::create([
            'title' => 'Test Article 1',
            'content' => 'This is test content for article 1',
            'author' => 'John Doe',
            'source' => 'newsapi',
            'category' => 'Technology',
            'published_at' => '2024-01-01 10:00:00',
            'source_id' => 'test-1',
            'url' => 'https://example.com/1',
            'url_to_image' => 'https://example.com/image1.jpg',
        ]);

        Article::create([
            'title' => 'Test Article 2',
            'content' => 'This is test content for article 2',
            'author' => 'Jane Smith',
            'source' => 'guardian',
            'category' => 'Politics',
            'published_at' => '2024-01-02 10:00:00',
            'source_id' => 'test-2',
            'url' => 'https://example.com/2',
            'url_to_image' => 'https://example.com/image2.jpg',
        ]);
    }

    public function test_can_get_articles_list()
    {
        $response = $this->getJson('/api/articles');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'current_page',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'content',
                            'author',
                            'source',
                            'category',
                            'published_at',
                            'url',
                            'url_to_image',
                        ]
                    ]
                ]);
    }

    public function test_can_search_articles()
    {
        $response = $this->getJson('/api/articles?q=Technology');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Test Article 1', $data[0]['title']);
    }

    public function test_can_filter_by_category()
    {
        $response = $this->getJson('/api/articles?category=Politics');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Politics', $data[0]['category']);
    }

    public function test_can_filter_by_source()
    {
        $response = $this->getJson('/api/articles?source=guardian');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('guardian', $data[0]['source']);
    }

    public function test_can_filter_by_date()
    {
        $response = $this->getJson('/api/articles?date=2024-01-01');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertStringContainsString('2024-01-01', $data[0]['published_at']);
    }

    public function test_can_get_categories()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                ->assertJsonStructure(['categories'])
                ->assertJson([
                    'categories' => ['Technology', 'Politics']
                ]);
    }

    public function test_can_get_sources()
    {
        $response = $this->getJson('/api/sources');

        $response->assertStatus(200)
                ->assertJsonStructure(['sources'])
                ->assertJson([
                    'sources' => ['newsapi', 'guardian']
                ]);
    }

    public function test_can_get_authors()
    {
        $response = $this->getJson('/api/authors');

        $response->assertStatus(200)
                ->assertJsonStructure(['authors'])
                ->assertJson([
                    'authors' => ['John Doe', 'Jane Smith']
                ]);
    }

    public function test_authenticated_user_can_get_preferences()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        UserPreference::create([
            'user_id' => $user->id,
            'sources' => ['newsapi'],
            'categories' => ['Technology'],
            'authors' => ['John Doe'],
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/preferences');

        $response->assertStatus(200)
                ->assertJson([
                    'sources' => ['newsapi'],
                    'categories' => ['Technology'],
                    'authors' => ['John Doe'],
                ]);
    }

    public function test_authenticated_user_can_update_preferences()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        $token = JWTAuth::fromUser($user);

        $preferences = [
            'sources' => ['guardian'],
            'categories' => ['Politics'],
            'authors' => ['Jane Smith'],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->postJson('/api/preferences', $preferences);

        $response->assertStatus(200)
                ->assertJson($preferences);

        $this->assertDatabaseHas('user_preferences', [
            'user_id' => $user->id,
            'sources' => json_encode(['guardian']),
            'categories' => json_encode(['Politics']),
            'authors' => json_encode(['Jane Smith']),
        ]);
    }

    public function test_articles_are_filtered_by_user_preferences()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        UserPreference::create([
            'user_id' => $user->id,
            'sources' => ['newsapi'],
            'categories' => ['Technology'],
            'authors' => ['John Doe'],
        ]);

        $token = JWTAuth::fromUser($user);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/articles');

        $response->assertStatus(200);
        $data = $response->json('data');
        
        // Should only return the guardian article since newsapi is in preferences
        $this->assertCount(1, $data);
        $this->assertEquals('guardian', $data[0]['source']);
    }
} 