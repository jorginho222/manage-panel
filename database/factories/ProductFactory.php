<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'trademark' => $this->faker->word(),
            'price' => $this->faker->numberBetween(200, 10000),
            'stock' => $this->faker->numberBetween(10, 300),
            'sells' => $this->faker->numberBetween(10, 300),
        ];
    }
}
