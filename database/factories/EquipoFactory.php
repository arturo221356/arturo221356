<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Equipo;
use Faker\Generator as Faker;

$factory->define(Equipo::class, function (Faker $faker) {
    return [
        'marca' => $faker->company(),
        'modelo' => $faker->companySuffix(),
        'precio' => $faker->randomNumber(4),
        'costo' => $faker->randomNumber(4),
    ];
});
