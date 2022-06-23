<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FreezingRoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'temp' => $this->faker->randomFloat(2, -10, 0),

        ];
    }
}
