<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['Empresa', 'Persona']),
            'name' => $this->faker->sentence(3),
            'identifier_number' => $this->faker->numerify('###########'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->numerify('###-##########'),
            'location' => $this->faker->city(),
            'address' => $this->faker->streetAddress(),
            'interests' => $this->faker->words(5, true),
        ];
    }
}
