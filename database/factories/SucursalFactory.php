<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sucursal;
use Faker\Generator as Faker;

$factory->define(Sucursal::class, function (Faker $faker) {
    return [
        'name' => $faker->city(),
        'address' => $faker->address(),
        'distribution_id' => $faker->numberBetween($min = 1, $max = 2),

    ];
});
