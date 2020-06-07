<?php

use Illuminate\Database\Seeder;

class ArticleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('article_types')->insert([
            [
                'title' => 'trip reports',
            ],
            [
                'title' => 'things to read',
            ],
        ]);
    }
}
