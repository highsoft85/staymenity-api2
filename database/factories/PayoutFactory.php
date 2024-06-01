<?php

declare(strict_types=1);

use App\Models\Payout;
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

$factory->define(Payout::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    return [
        'user_id' => $user_id,
        'provider' => Payout::PROVIDER_STRIPE,
        'provider_payout_id' => stripePaymentIntendTest(),
        'provider_transaction_id' => stripePaymentIntendTest(),
        'amount' => rand(10, 500),
        'status' => Payout::STATUS_ACTIVE,
    ];
});
