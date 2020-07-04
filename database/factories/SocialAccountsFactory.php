<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SocialAccount;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(SocialAccount::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'avatar' => $faker->imageUrl,
        'provider_id' => (string)$faker->randomNumber,
        'provider' => 'google',
        'user_id' => 1
    ];
});
