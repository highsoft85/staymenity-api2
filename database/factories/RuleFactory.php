<?php

declare(strict_types=1);

use App\Models\Rule;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Support\Str;

/** @var Factory $factory */

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

$factory->define(Rule::class, function (Faker $faker, array $data) {
    $title = $data['title'] ?? $faker->firstName;
    return [
        'type' => Rule::TYPE_LISTING,
        'name' => Str::slug($title),
        'title' => $title,
        'priority' => 0,
        'status' => Rule::STATUS_ACTIVE,
    ];
});
