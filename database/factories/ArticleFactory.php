<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Article;
use Faker\Generator as Faker;

$factory->define(Article::class, function (Faker $faker) {
    return [
        'body' => $faker->text(333),
        'type' => $faker->randomElement([1,2,3]),
    ];
});
