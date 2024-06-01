<?php

declare(strict_types=1);

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\User;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;
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

$factory->define(User::class, function (Faker $faker) {
    $email = $faker->unique()->safeEmail;
    $phone = $faker->phoneNumber;
    $phone = '+1-864-926-' . rand(1000, 9999);
    return [
        'login' => $email,
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'phone' => $phone,
        'email' => $email,
        'email_verified_at' => now(),
        'password' => Hash::make('secret'),
        'remember_token' => Str::random(10),
    ];
});
