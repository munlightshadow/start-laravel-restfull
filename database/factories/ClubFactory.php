<?php

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

$factory->define(App\Models\Club::class, function (Faker $faker) {

    return [
        'title' => $faker->company,
        'description' => $faker->text(200),
        'frequency' => $faker->text(50),
        'price' => $faker->numberBetween(5, 100),
        'taxes' => $faker->numberBetween(0, 50),
        'quantity' => $faker->numberBetween(0, 50),
        'avatar' => \App\Helpers\S3Helper::addFileFromUrl('https://fakeimg.pl/640x480?'.rand(), 'club/avatar')->id
    ];
});
