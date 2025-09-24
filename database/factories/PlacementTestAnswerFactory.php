<?php

namespace Database\Factories;

use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlacementTestAnswer>
 */
class PlacementTestAnswerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'attempt_id' => PlacementTestAttempt::factory(),
            'question_id' => PlacementTestQuestion::factory(),
            'selected_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'is_correct' => $this->faker->boolean,
            'score_awarded' => $this->faker->numberBetween(0, 1)
        ];
    }
}
