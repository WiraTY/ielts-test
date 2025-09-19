<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CourseList extends Component
{
    public function render()
    {
        $courses = Course::where('is_trial', true)
            ->whereNotNull('published_at')
            ->orderBy('order')
            ->get();

        // Calculate progress for each course if user is authenticated
        if (auth()->check()) {
            foreach ($courses as $course) {
                $progress = $course->getUserProgress(auth()->id());
                $course->is_completed = $progress['is_completed'];
                $course->progress_percentage = $progress['percentage'];
            }
        }

        return view('livewire.course-list', compact('courses'));
    }
}