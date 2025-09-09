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

        return view('livewire.course-list', compact('courses'));
    }
}
