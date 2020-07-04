<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Http\Controllers\AuthController;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    /**
     * @var AuthController
     */
    private $controller;

    protected function setUp(): void {
        parent::setUp();

        $this->controller = new AuthController();
    }

    /**
     * Unique username test.
     *
     * @return void
     */
    public function testGetProviderAuthUrl(): void
    {
        $response = $this->json('GET', "api/v1/auth/google/url");
        $response->assertStatus(200);

        $response->assertJsonStructure(['url']);
    }
}
