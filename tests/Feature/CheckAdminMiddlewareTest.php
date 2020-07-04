<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Tests\TestCase;
use App\Models\User;
use UserSeeder;
use RoleSeeder;
use RoleUserSeeder;
use App\Http\Middleware\CheckAdmin;

class CheckAdminMiddlewareTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected function setUp(): void {
        parent::setUp();

        $this->seed(UserSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RoleUserSeeder::class);
    }

    public function testUserIsAdmin()
    {
        $user = User::find(1);
        $this->actingAs($user);

        $request = Request::create('api/v1/admin/article/approve', 'POST', ['article_id' => 1]);

        $middleware = new CheckAdmin;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response, null);
    }

    public function testUserNotAdmin()
    {
        $user = User::find(2);
        $this->actingAs($user);

        $request = Request::create('api/v1/admin/article/approve', 'POST', ['article_id' => 1]);

        $middleware = new CheckAdmin;

        $response = $middleware->handle($request, function () {});

        $this->assertEquals($response->getStatusCode(), 401);
    }
}
