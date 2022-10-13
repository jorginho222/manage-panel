<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Provider>
 */
class ProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'company_name' => $this->faker->sentence(3),
            'about' => $this->faker->paragraph(2),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('###-##########'),
            'location' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
        ];
    }
}
