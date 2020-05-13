<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Equipo;
use Faker\Generator as Faker;
use App\Distribution;

$factory->define(Equipo::class, function (Faker $faker) {
    $distribution = Distribution::all()->pluck('id');
    return [
        'marca' => $faker->company(),
        'modelo' => $faker->companySuffix(),
        'precio' => $faker->randomNumber(4),
        'costo' => $faker->randomNumber(4),
        'distribution_id' => $faker->randomElement($distribution),
    ];
});
