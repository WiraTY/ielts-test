<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Progress;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class CourseDetail extends Component
{
    public Course $course;

    public function mount(Course $course)
    {
        // Check if user is authorized to view this course
        Gate::authorize('view', $course);
        
        $this->course = $course;
    }

    public function render()
    {
        $lessons = $this->course->lessons()->orderBy('order')->get();
        
        // Get progress for authenticated user
        if (auth()->check()) {
            $progress = Progress::where('user_id', auth()->id())
                ->whereIn('lesson_id', $lessons->pluck('id'))
                ->get()
                ->keyBy('lesson_id');
        } else {
            $progress = collect();
        }
        
        return view('livewire.course-detail', compact('lessons', 'progress'));
    }
}
