<?php

use App\Models\Credential;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'nickname' => $faker->userName,
        'email' => $faker->email,
        'avatar' => $faker->imageUrl,
    ];
});

$factory->define(Credential::class, function (Faker\Generator $faker) {
    return [
        'provider' => $faker->randomElement(['twitter', 'github']),
        'provider_id' => $faker->randomNumber(6),
        'token' => $faker->md5($faker->randomNumber()),
        'token_secret' => bcrypt($faker->md5($faker->randomNumber())),
    ];
});
