<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        DB::table('messages')->insert([
            [
                'body' => $faker->text,
                'attachments' => json_encode(['valid' => 'json']),
                'timestamp' => round(microtime(true) * 1000),
                'room_id' => 1,
                'user_id' => 1,
            ],
            [
                'body' => $faker->text,
                'attachments' => json_encode(['valid' => 'json']),
                'timestamp' => round(microtime(true) * 1000) + 1,
                'room_id' => 1,
                'user_id' => 1,
            ],
            [
                'body' => $faker->text,
                'attachments' => json_encode(['valid' => 'json']),
                'timestamp' => round(microtime(true) * 1000) + 2,
                'room_id' => 1,
                'user_id' => 1,
            ],
        ]);
    }
}
