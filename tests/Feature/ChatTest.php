<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;
use App\Http\Controllers\ChatController;
use Illuminate\Http\JsonResponse;
use MessageSeeder;
use UserSeeder;
use RoomSeeder;
use RoleSeeder;
use RoleUserSeeder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Event;

class ChatTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;
    use WithoutMiddleware;

    /**
     * @var ChatController
     */
    private $controller;

    protected function setUp(): void {
        parent::setUp();

        $this->controller = $this->app->make('App\Http\Controllers\ChatController');
        $this->seed(UserSeeder::class);
        $this->seed(RoleSeeder::class);
        $this->seed(RoleUserSeeder::class);
        $this->seed(RoomSeeder::class);
        $this->seed(MessageSeeder::class);
    }

    protected function tearDown(): void {
        $images = File::files(storage_path('app/public/uploads/images'));
        File::delete($images);

        parent::tearDown();
    }

    public function testMessageHistory(): void
    {
        $response = $this->json('GET', "api/v1/chat/message/history/1");
        $response->assertStatus(200);
        $response = $this->json('GET', "api/v1/chat/message/history/255");
        $response->assertStatus(422);
    }

    public function testMessageBroadcasting(): void
    {
        Event::fake();

        $response = $this->json('POST', 'api/v1/chat/message', [
            'body' => 'Lorem<html> ipsum dolor sit amet consectetur adipisicing elit. Aperiam, nulla, accusantium laborum rem alias consectetur necessitatibus fuga tenetur, perferendis ipsum quasi perspiciatis voluptatem distinctio mollitia corporis esse recusandae amet rerum!',
            'timestamp' => 1588508529000,
            'room_id' => 1,
            'user_id' => 1,
            'attachments' => [
                [
                    'type' => 'image',
                    'source' => 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAUAAAAFCAYAAACNbyblAAAAHElEQVQI12P4//8/w38GIAXDIBKE0DHxgljNBAAO9TXL0Y4OHwAAAABJRU5ErkJggg=='
                ]
            ]
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('messages', [
            'user_id' => 1,
            'room_id' => 1,
        ]);

        Event::assertNotDispatched(\App\Events\MessageReceived::class, function ($e) {
            $this->assertEquals(1, $e->roomId);
        });
    }

    public function testBanUserWithErasingMessages(): void
    {
        $messages = \App\Models\Message::where('user_id', 3)->get();
        $this->assertEquals(1, $messages->count());

        $response = $this->json('POST', 'api/v1/admin/chat/ban', [
            'userId' => 3,
            'deleteMessageHistory' => true
        ]);

        $response->assertStatus(200);
        $user = \App\Models\User::find(3);
        $bannedRole = \App\Models\Role::find(1);
        $result = $user->roles()->get()->contains($bannedRole);
        $this->assertEquals(true, $result);
        $messages = \App\Models\Message::where('user_id', 3)->get();
        $this->assertEquals(0, $messages->count());
    }

    public function testBanUserWithoutErasingMessages(): void
    {
        $response = $this->json('POST', 'api/v1/admin/chat/ban', [
            'userId' => 3,
            'deleteMessageHistory' => false
        ]);

        $response->assertStatus(200);
        $user = \App\Models\User::find(3);
        $bannedRole = \App\Models\Role::find(1);
        $result = $user->roles()->get()->contains($bannedRole);
        $this->assertEquals(true, $result);
    }
}
