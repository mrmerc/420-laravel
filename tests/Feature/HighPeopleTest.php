<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Http\Controllers\HighPeopleController;
use App\Services\HighPeopleService;
use Widmogrod\Monad\Either\{Right};

class HighPeopleTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * @var HighPeopleController
     */
    private $controller;
    /**
     * @var HighPeopleService
     */
    private $service;

    protected function setUp(): void {
        parent::setUp();
        $this->seed(\HighPeopleSeeder::class);
        $this->controller = $this->app->make('App\Http\Controllers\HighPeopleController');
        $this->service = new HighPeopleService();
    }

    public function testGetHighPeopleCounter()
    {
        $response = $this->json('GET', 'api/v1/high/people');
        $response->assertStatus(200);
        $response->assertJson([
            'count' => 3
        ]);
    }

    public function testIncrementHighPeopleCounter()
    {
        $response = $this->json('PUT', 'api/v1/high/people');
        $response->assertStatus(200);
        $response->assertJson([
            'count' => 4
        ]);
        $this->assertDatabaseHas('high_people', [
            'id' => 1,
            'count' => 4,
        ]);
    }

    public function testDropCounter()
    {
        $result = $this->service->dropCounter();

        $this->assertInstanceOf(Right::class, $result);
    }
}
