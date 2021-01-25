<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(\App\Models\Eloquents\UserProfile::class, function (Faker $faker) {
    return [
        'name' => Str::random(),
        'description' => Str::random(),
        'icon' => sprintf('%s.jpeg', Str::uuid()),
    ];
});
