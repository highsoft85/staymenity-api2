<?php

declare(strict_types=1);

use App\Models\Listing;
use App\Models\ListingSetting;
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

$factory->define(ListingSetting::class, function (Faker $faker, array $data) {
    $listing_id = $data['listing_id'] ?? null;
    if (is_null($listing_id)) {
        $listing_id = factory(Listing::class)->create()->id;
    }
    return [
        'listing_id' => $listing_id,
        'amenities' => null,
        'type' => null,
        'rules' => null,
        //'is_accept_whole_day' => $faker->numberBetween(0, 1),
        'is_dedicated' => $faker->numberBetween(0, 1),
        'is_company' => $faker->numberBetween(0, 1),
        'is_rented_before' => $faker->numberBetween(0, 1),
        //'nights_min' => $faker->numberBetween(1, 10),
        //'nights_max' => $faker->numberBetween(10, 20),
        'people_max' => $faker->numberBetween(3, 18),
        'cancellation_description' => $faker->realText(100),
    ];
});
