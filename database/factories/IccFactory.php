<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Icc;
use Faker\Generator as Faker;
use App\Distribution;

$factory->define(Icc::class, function (Faker $faker) {
    $distribution = Distribution::all()->pluck('id');

    return [

        'icc' => $faker->shuffle('qwertyuiopasdfghjkl'),
        'status_id' => $faker->numberBetween($min = 1, $max = 5),
        'sucursal_id' => $faker->numberBetween($min = 1, $max = 10),
        'distribution_id' => $faker->randomElement($distribution),

    ];
});
