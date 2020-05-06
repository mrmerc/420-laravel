<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the user seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'nickname#0',
                'email' => 'nickname@example.com',
                'password' => Hash::make('password'),
                'name' => 'nickname',
            ],
            [
                'username' => 'bannedUser#0',
                'email' => 'banned@example.com',
                'password' => Hash::make('password'),
                'name' => 'banned',
            ],
        ]);
    }
}
