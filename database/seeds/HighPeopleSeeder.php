<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HighPeopleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('high_people')->insert([
            'count' => 3,
        ]);
    }
}
