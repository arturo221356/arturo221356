<?php

namespace Database\Factories;

use App\Equipo;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EquipoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Equipo::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'marca' => $this->faker->company(),
            'modelo' => $this->faker->companySuffix(),
            'precio' => $this->faker->randomNumber(4),
            'costo' => $this->faker->randomNumber(4),
            'distribution_id' => $this->faker->randomElement([1,2]),
        ];
    }
}
