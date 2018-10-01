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

$factory->define(App\Models\Review::class, function (Faker $faker) {
    $type = $faker->randomElement(['CLUB','INSTITUTION','TASTING','PRODUCT']);

    switch ($type)
    {
        case 'INSTITUTION':
            $instance_id = \App\Models\Institution::inRandomOrder()->first()->id;
            break;

        case 'CLUB':
            $instance_id = \App\Models\Club::inRandomOrder()->first()->id;
            break;

        case 'TASTING':
            $instance_id = \App\Models\Tasting::inRandomOrder()->where('is_parent', false)->first()->id;
            break;

        case 'PRODUCT':
            $instance_id = \App\Models\Product::inRandomOrder()->first()->id;
            break;
    }
    return [
        'description' => $faker->text(200),
        'type' => $type,
        'instance_id' => $instance_id,
        'verify_status' => 1,
        'rating' => $faker->numberBetween(1, 5)
    ];
});
