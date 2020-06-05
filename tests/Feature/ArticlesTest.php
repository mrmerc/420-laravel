<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ArticlesTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function testGetArticleById(): void
    {
        factory(\App\Models\Article::class, 3)->create();

        $response = $this->json('GET', "api/v1/article/2");
        $response->assertStatus(200);
        $response->assertJson(
            [
                'article' => \App\Models\Article::find(2)->toJson()
            ]
        );
    }

    public function testSubmitArticle(): void
    {
        $response = $this->json('POST', 'api/v1/article', [
            'body' => 'Article test body',
            'type' => 0
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('articles', [
            'body' => 'Article test body',
            'type' => 0,
            'is_available' => false
        ]);
    }
}
