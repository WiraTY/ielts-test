<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence;
        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'description' => $this->faker->paragraph,
            'is_trial' => true,
            'level' => $this->faker->randomElement(['starter', 'beginner', 'elementary', 'intermediate', 'advanced']),
            'published_at' => $this->faker->dateTime,
            'created_by' => User::factory() // This will create an admin user
        ];
    }
}
