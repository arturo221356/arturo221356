<?php

namespace Database\Factories;

use App\Imei;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ImeiFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Imei::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'imei' => $this->faker->numerify('12345##########'),
            'inventario_id' =>$this->faker->numberBetween($min = 1, $max = 4),
            'equipo_id' => $this->faker->numberBetween($min = 1, $max = 10),
        ];
    }
}
