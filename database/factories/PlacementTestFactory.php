<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlacementTest>
 */
class PlacementTestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'duration_minutes' => $this->faker->numberBetween(10, 60),
            'is_active' => true,
            'level_mapping' => [
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ]
        ];
    }
}
