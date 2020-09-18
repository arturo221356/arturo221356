<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Imei;
use Faker\Generator as Faker;

$factory->define(Imei::class, function (Faker $faker) {
    return [
        'imei' => $faker->numerify('12345##########'),
        'inventario_id' => $faker->numberBetween($min = 1, $max = 4),
        'equipo_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});
