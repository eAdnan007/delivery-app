<?php

use Faker\Generator as Faker;

$factory->define(App\Order::class, function (Faker $faker) {
    return [
        'status' => 'UNASSIGNED',
        'distance' => $faker->numberBetween(1, 9999999)
    ];
});
