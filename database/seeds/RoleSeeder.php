<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'title' => 'banned',
                'weight' => 0,
            ],
            [
                'title' => 'user',
                'weight' => 1,
            ],
            [
                'title' => 'manager',
                'weight' => 2,
            ],
            [
                'title' => 'admin',
                'weight' => 3,
            ],
            [
                'title' => 'superadmin',
                'weight' => 4,
            ],
        ]);
    }
}
