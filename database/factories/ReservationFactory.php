<?php

declare(strict_types=1);

use App\Models\Reservation;
use App\Models\User;
use App\Models\Listing;
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

$factory->define(Reservation::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    $listing_id = $data['listing_id'] ?? null;
    if (is_null($listing_id)) {
        $listing_id = factory(Listing::class)->create()->id;
    }
    $date = \Carbon\Carbon::parse($faker->date());
    $price = rand(100, 500);
    $service_fee = Reservation::SERVICE_FEE;
    return [
        'user_id' => $user_id,
        'listing_id' => $listing_id,
        'message' => $faker->realText(100),
        'start_at' => $date->format(\App\Services\Model\UserReservationServiceModel::DATE_FORMAT),
        'finish_at' => $date->addHours(rand(1, 10))->endOfHour()->format(\App\Services\Model\UserReservationServiceModel::DATE_FORMAT),
        'is_agree' => 1,
        'guests_size' => rand(3, 10),
        'total_price' => $price + $service_fee,
        'price' => $price,
        'service_fee' => $service_fee,
        'free_cancellation_at' => $date,
        'status' => Reservation::STATUS_DRAFT,
        'code' => Str::upper(Str::random(\App\Services\Model\UserReservationServiceModel::CODE_LENGTH)),
        'timezone' => Listing::find($listing_id)->timezone,
    ];
});
