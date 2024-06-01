<?php

declare(strict_types=1);

use Faker\Generator as Faker;
use App\Models\Location;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Database\Eloquent\Factory;

/** @var Factory $factory */

$factory->define(Location::class, function (Faker $faker, array $data) {
    $user_id = $data['user_id'] ?? null;
    if (is_null($user_id)) {
        $user_id = factory(User::class)->create()->id;
    }
    return [
        'type' => Location::TYPE_DEFAULT,
        'country_id' => null,
        'locationable_id' => $user_id,
        'locationable_type' => User::class,
        //'latitude' => 32.71571100,
        //'longitude' => -117.15461400,
        'point' => [32.71571100, -117.15461400],
        'zoom' => 11,
        'title' => 'New York',
        'text' => 'United States of America, New York',
        'address' => '9279 Central Ave. Brooklyn, NY 11230',
        'locality' => 'New York',
        'province' => 'New York',
        'province_code' => 'NY',
        'country' => 'United States of America',
        'country_code' => 'US',
        'zip' => '9279',
        'status' => Location::STATUS_ACTIVE,
    ];
});
