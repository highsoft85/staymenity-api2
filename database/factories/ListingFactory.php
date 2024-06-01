<?php

declare(strict_types=1);

use App\Models\Listing;
use App\Models\Type;
use App\Models\User;
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

$factory->define(Listing::class, function (Faker $faker, array $data) {
    $title = $data['title'] ?? $faker->company;
    $name = $data['name'] ?? Str::slug($title);
    $type_id = $data['type_id'] ?? null;
    if (is_null($type_id)) {
        $type_id = factory(Type::class)->create()->id;
    }
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    $price = $faker->numberBetween(1, 50);
    return [
        'creator_id' => $user_id,
        'user_id' => $user_id,
        'owner_id' => $user_id,
        'name' => $name,
        'title' => $title,
        'type_id' => $type_id,
        'description' => $faker->realText(100),
        'price' => $price,
        'price_per_day' => $price * 24,
        'deposit' => null,
        'cleaning_fee' => null,
        'guests_size' => $faker->numberBetween(3, 18),
        'banned_at' => null,
        'status' => Listing::STATUS_ACTIVE,
        'timezone' => config('app.timezone'),
    ];
});
