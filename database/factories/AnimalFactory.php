<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Animal>
 */
class AnimalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'animal_number' => fake()->numberBetween(1, 100),
            'type_name' => fake()->randomElement(['cow', 'sheep', 'goat', 'chicken']),
            'years' => fake()->numberBetween(1, 20),
        ];
    }
}
