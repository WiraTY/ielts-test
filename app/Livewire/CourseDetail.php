<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class CourseDetail extends Component
{
    public Course $course;
    public $courseProgress;

    public function mount(Course $course)
    {
        // Check if user is authorized to view this course
        Gate::authorize('view', $course);
        
        $this->course = $course;
        
        // Calculate course progress if user is authenticated
        if (auth()->check()) {
            $this->courseProgress = $course->getUserProgress(auth()->id());
        }
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
    
    public function enroll()
    {
        // Check if user is authorized to view this course
        Gate::authorize('view', $this->course);
        
        // Get the first lesson of the course
        $firstLesson = $this->course->lessons()->orderBy('order')->first();
        
        if (!$firstLesson) {
            // Handle case where course has no lessons
            session()->flash('error', 'This course has no lessons available.');
            return;
        }
        
        // Redirect to the first lesson
        return redirect()->route('lessons.show', [
            'course' => $this->course->slug,
            'lesson' => $firstLesson->slug
        ]);
    }
}
