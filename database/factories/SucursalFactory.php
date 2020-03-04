<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Sucursal;
use Faker\Generator as Faker;

$factory->define(Sucursal::class, function (Faker $faker) {
    return [
        'nombre_sucursal' => $faker->city(),
        'direccion_sucursal' => $faker->address(),
        'email_sucursal' => $faker->email(),

    ];
});
