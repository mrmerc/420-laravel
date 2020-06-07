<?php

namespace Tests\Feature;

use ArticleTypeSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use WithoutMiddleware;

    protected function setUp(): void {
        parent::setUp();

        $this->seed(ArticleTypeSeeder::class);
    }

    public function testGetArticleById(): void
    {
        factory(\App\Models\Article::class, 3)->create();

        $response = $this->json('GET', "api/v1/article/2");
        $response->assertStatus(200);
        $response->assertJson([
            'article' => \App\Models\Article::find(2)->toJson()
        ]);
        $response = $this->json('GET', "api/v1/article/huy");
        $response->assertStatus(422);
    }

    public function testSubmitArticle(): void
    {
        $response = $this->json('POST', 'api/v1/article', [
            'body' => 'Article test body',
            'typeId' => 1
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'body' => 'Article test body',
            'typeId' => 1,
            'is_available' => false
        ]);
    }

    public function testSubmitArticleWithNotExistingType(): void
    {
        $response = $this->json('POST', 'api/v1/article', [
            'body' => 'Article test body',
            'typeId' => 5
        ]);
        $response->assertStatus(422);
    }

    public function testSetArticleAvailable(): void
    {
        factory(\App\Models\Article::class, 1)->create();

        $response = $this->json('POST', 'api/v1/admin/article/approve', [
            'articleId' => 1
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'id' => 1,
            'is_available' => true
        ]);
    }
}
