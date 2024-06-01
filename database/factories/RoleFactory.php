<?php

declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Spatie\Permission\Models\Role;
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

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->name,
        'title' => $faker->firstName,
        'guard_name' => 'web',
    ];
});
