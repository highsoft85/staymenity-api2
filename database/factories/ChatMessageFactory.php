<?php

declare(strict_types=1);

use App\Models\ChatMessage;
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

$factory->define(ChatMessage::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(\App\Models\User::class)->create()->id;
    }
    $chat_id = $data['chat_id'] ?? null;
    if (is_null($chat_id)) {
        $chat_id = factory(\App\Models\Chat::class)->create()->id;
    }
    return [
        'user_id' => $user_id,
        'chat_id' => $chat_id,
        'text' => $faker->realText(rand(50, 200)),
        'read_at' => null,
        'send_at' => now()->subMinutes(rand(10, 400)),
        'status' => ChatMessage::STATUS_ACTIVE,
    ];
});
