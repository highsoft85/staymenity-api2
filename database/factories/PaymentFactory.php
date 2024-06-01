<?php

declare(strict_types=1);

use App\Models\Payment;
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

$factory->define(Payment::class, function (Faker $faker, array $data) {
    $user_from_id = $data['user_from_id'] ?? null;
    if (is_null($user_from_id)) {
        $user_from_id = factory(User::class)->create()->id;
    }
    $user_to_id = $data['user_to_id'] ?? null;
    if (is_null($user_to_id)) {
        $user_to_id = factory(User::class)->create()->id;
    }
    return [
        'user_from_id' => $user_from_id,
        'user_to_id' => $user_to_id,
        'provider' => Payment::PROVIDER_STRIPE,
        'provider_payment_id' => stripePaymentIntendTest(),
        'amount' => rand(10, 500),
        'service_fee' => \App\Models\Reservation::SERVICE_FEE,
        'status' => Payment::STATUS_ACTIVE,
    ];
});
