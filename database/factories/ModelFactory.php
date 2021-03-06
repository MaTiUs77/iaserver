<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your Model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default Model should look.
|
*/

$factory->define(IAServer\User::class, function ($faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => str_random(10),
        'remember_token' => str_random(10),
    ];
});
