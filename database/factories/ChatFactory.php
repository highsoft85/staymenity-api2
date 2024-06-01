<?php

declare(strict_types=1);

use App\Models\Chat;
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

$factory->define(Chat::class, function (Faker $faker, array $data) {
    $owner_id = $data['owner_id'] ?? null;
    if (is_null($owner_id)) {
        $owner_id = factory(\App\Models\User::class)->create()->id;
    }
    $creator_id = $data['creator_id'] ?? null;
    if (is_null($creator_id)) {
        $creator_id = factory(\App\Models\User::class)->create()->id;
    }
//    $reservation_id = $data['reservation_id'] ?? null;
//    if (is_null($reservation_id)) {
//        $reservation_id = factory(\App\Models\Reservation::class)->create()->id;
//    }
    $listing_id = $data['listing_id'] ?? null;
    if (is_null($listing_id)) {
        $listing_id = factory(\App\Models\Listing::class)->create()->id;
    }
    return [
        'owner_id' => $owner_id,
        'creator_id' => $creator_id,
        //'reservation_id' => $reservation_id,
        'listing_id' => $listing_id,
        'title' => $faker->company,
        'status' => Chat::STATUS_ACTIVE,
    ];
});
