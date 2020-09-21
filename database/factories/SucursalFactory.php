<?php

namespace Database\Factories;

use App\Sucursal;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SucursalFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Sucursal::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->city(),
            'address' =>  $this->faker->address(),
            'distribution_id' =>  $this->faker->numberBetween($min = 1, $max = 2),

        ];
    }
}
