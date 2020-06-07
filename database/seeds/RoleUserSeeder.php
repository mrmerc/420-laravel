<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role_user')->insert([
            [
                'role_id' => 2, // User
                'user_id' => 1,
            ],
            [
                'role_id' => 4, // Superadmin
                'user_id' => 1,
            ],
            [
                'role_id' => 1, // Banned
                'user_id' => 2,
            ],
            [
                'role_id' => 2, // User
                'user_id' => 3,
            ],
        ]);
    }
}
