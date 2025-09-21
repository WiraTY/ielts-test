<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlacementTestAttempt>
 */
class PlacementTestAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'started_at' => $this->faker->dateTime,
            'finished_at' => $this->faker->dateTime,
            'score' => $this->faker->numberBetween(0, 100),
            'assigned_level' => $this->faker->randomElement(['starter', 'beginner', 'elementary', 'intermediate', 'advanced']),
            'status' => $this->faker->randomElement(['in_progress', 'completed', 'timeout'])
        ];
    }
}
