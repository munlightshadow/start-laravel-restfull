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

$factory->define(App\Models\Tasting::class, function (Faker $faker) {

    $type = $faker->randomElement(['MONTHLY','WEEKLY','DAILY','SINGLE']);
    $date = \Carbon\Carbon::parse($faker->date('Y-m-d'));
    $dateStart = $date;
    $dateStart = $dateStart->format('Y-m-d');
    switch($type){
        case 'MONTHLY':
            $dateEnd = $date->addMonths($faker->randomDigitNotNull)->format('Y-m-d');
            break;
        case 'WEEKLY':
            $dateEnd = $date->addWeeks($faker->randomDigitNotNull)->format('Y-m-d');
            break;
        case 'DAILY':
            $dateEnd = $date->addDays($faker->randomDigitNotNull)->format('Y-m-d');
            break;
        case 'SINGLE':
            $dateEnd = $dateStart;
            break;
    }

    return [
        'title' => $faker->company,
        'description' => $faker->text(200),
        'location_id' => null,
        'price' => $faker->numberBetween(5, 100),
        'tickets' => $faker->numberBetween(0, 50),
        'avatar' => \App\Helpers\S3Helper::addFileFromUrl('https://fakeimg.pl/640x480?'.rand(), 'tasting/avatar')->id,
        'dateStart' => $dateStart,
        'dateEnd' => $dateEnd,
        'timeStart' => $faker->numberBetween(8,13).':'.$faker->numberBetween(0,5).'0',
        'timeEnd' => $faker->numberBetween(13,23).':'.$faker->numberBetween(0,5).'0',
        'tickets' => $faker->randomDigit,
        'price' => $faker->numberBetween(5, 100),
        'dates' => $faker->randomDigit,
        'date' => null,
        'type' => $type,
        'is_parent' => true
    ];
});
