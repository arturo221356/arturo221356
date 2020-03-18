<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Icc;
use Faker\Generator as Faker;

$factory->define(Icc::class, function (Faker $faker) {
    return [
        
        'icc' => $faker->shuffle('qwertyuiopasdfghjklz'),
        'status_id' => $faker->numberBetween($min = 1, $max = 5),
        'sucursal_id' => $faker->numberBetween($min = 1, $max = 50),
        'sub_product_id' => 1,
    ];
});
