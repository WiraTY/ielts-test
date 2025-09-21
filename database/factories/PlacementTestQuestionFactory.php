<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PlacementTestQuestion>
 */
class PlacementTestQuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $options = [
            'A' => $this->faker->sentence,
            'B' => $this->faker->sentence,
            'C' => $this->faker->sentence,
            'D' => $this->faker->sentence
        ];
        
        return [
            'question_text' => $this->faker->sentence,
            'options' => $options,
            'correct_answer' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'score' => 1,
            'order' => $this->faker->numberBetween(1, 100)
        ];
    }
}
