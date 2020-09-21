<?php

namespace Database\Factories;

use App\Icc;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IccFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Icc::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'icc' => $this->faker->numerify('895203#############'),
            'inventario_id' =>  $this->faker->numberBetween($min = 1, $max = 4),
            'company_id' => 2,
        ];
    }
}
