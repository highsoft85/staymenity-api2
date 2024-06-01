<?php

declare(strict_types=1);

use App\Models\UserSave;
use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

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

$factory->define(UserSave::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    $title = $data['title'] ?? $faker->firstName;
    return [
        'user_id' => $user_id,
        'title' => $title,
        'status' => UserSave::STATUS_ACTIVE,
    ];
});
