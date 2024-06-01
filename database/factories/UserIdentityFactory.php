<?php

declare(strict_types=1);

use App\Models\UserIdentity;
use App\Models\User;
use App\Models\Listing;
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

$factory->define(UserIdentity::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    return [
        'type' => UserIdentity::TYPE_PASSPORT,
        'user_id' => $user_id,
        'autohost_reservation_id' => \Illuminate\Support\Str::random(16),
        'status' => 1,
    ];
});
