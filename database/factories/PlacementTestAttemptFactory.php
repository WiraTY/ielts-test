<?php

namespace Database\Factories;

use App\Models\PlacementTest;
use App\Models\User;
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
            'placement_test_id' => PlacementTest::factory(),
            'user_id' => User::factory(),
            'started_at' => $this->faker->dateTime,
            'finished_at' => $this->faker->dateTime,
            'score' => $this->faker->numberBetween(0, 100),
            'assigned_level' => $this->faker->randomElement(['starter', 'beginner', 'elementary', 'intermediate', 'advanced']),
            'status' => $this->faker->randomElement(['in_progress', 'completed', 'timeout'])
        ];
    }
}
