<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Icc;
use Faker\Generator as Faker;
use App\Distribution;

$factory->define(Icc::class, function (Faker $faker) {
    $distribution = Distribution::all()->pluck('id');

    return [

        'icc' => $faker->numerify('895203#############'),
        'status_id' => $faker->numberBetween($min = 1, $max = 5),
        'sucursal_id' => $faker->numberBetween($min = 1, $max = 10),
        'company_id' => 2,

    ];
});
