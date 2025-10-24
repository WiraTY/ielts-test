<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lesson>
 */
class LessonFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(4);
        
        return [
            'course_id' => Course::factory(),
            'slug' => \Str::slug($title),
            'title' => $title,
            'content' => $this->faker->paragraph(),
            'video_url' => $this->faker->optional()->url(),
            'order' => $this->faker->numberBetween(1, 10),
        ];
    }
}