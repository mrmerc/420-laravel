<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use App\Http\Controllers\AuthController;
use UserSeeder;
use Widmogrod\Monad\Either\{Right};

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
    public function testUserCreatedWithUniqueNickname(): void
    {
        $this->seed(UserSeeder::class);

        $data = [
            'email' => 'nickname@gmail.com',
            'name' => 'Vasily Andreev',
            'id' => '12345678',
            'avatar' => 'https://dummyimage.com/600x400/000/fff',
        ];

        $token = $this->controller->login($data);

        $this->assertInstanceOf(Right::class, $token);
        $this->assertDatabaseHas('users', [
            'email' => 'nickname@gmail.com',
            'username' => 'nickname#1',
        ]);

        $this->assertNotNull($token->extract());
    }

    /**
     * Find existing user test.
     *
     * @return void
     */
    public function testFindExistingUser(): void
    {
        $this->seed(UserSeeder::class);

        $data = [
            'email' => 'nickname@example.com',
        ];

        $token = $this->controller->login($data);

        $this->assertInstanceOf(Right::class, $token);
        $this->assertDatabaseMissing('users', [
            'email' => 'nickname@gmail.com',
            'username' => 'nickname#1',
        ]);

        $this->assertNotNull($token->extract());
    }
}
