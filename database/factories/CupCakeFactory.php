<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CupCake>
 */
class CupCakeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'image' => fake()->imageUrl($width = 640, $height = 480),
            'quantity' => fake()->randomNumber(2),
            'price' => fake()->randomNumber(1),
        ];
    }
}
