<?php

declare(strict_types=1);

use App\Models\UserCalendar;
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

$factory->define(UserCalendar::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    $listing_id = $data['listing_id'] ?? null;
    if (is_null($listing_id)) {
        $listing_id = factory(Listing::class)->create()->id;
    }
    $title = $data['title'] ?? $faker->firstName;
    return [
        'type' => UserCalendar::TYPE_LOCKED,
        'user_id' => $user_id,
        'listing_id' => $listing_id,
        'date_at' => $faker->dateTimeThisMonth,
        'is_weekend' => 0,
        'status' => UserCalendar::STATUS_ACTIVE,
    ];
});
