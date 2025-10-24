<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;

class CourseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Set initial order values for existing courses
        $courses = Course::orderBy('created_at')->get();
        foreach ($courses as $index => $course) {
            $course->update(['order' => $index]);
        }
    }
}
