<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Option>
 */
class OptionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'answer' => fake()->randomElement([fake()->word, fake()->randomNumber(2)]),
            'is_correct' => false,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
