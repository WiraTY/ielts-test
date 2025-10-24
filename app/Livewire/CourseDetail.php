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
    public $isCourseAccessible = true;

    public function mount(Course $course)
    {
        // Check if user is authorized to view this course
        Gate::authorize('view', $course);
        
        // Check if user can access this course based on their level (for UI purposes)
        $this->isCourseAccessible = true;
        if (auth()->check() && !$this->canUserAccessCourse(auth()->user(), $course)) {
            $this->isCourseAccessible = false;
        }
        
        $this->course = $course;
        
        // Calculate course progress if user is authenticated
        if (auth()->check()) {
            $this->courseProgress = $course->getUserProgress(auth()->id());
        }
    }
    
    /**
     * Check if user can access the course based on their level
     */
    private function canUserAccessCourse($user, $course)
    {
        // Admins can access all courses
        if ($user->isAdmin()) {
            return true;
        }
        
        // Users who haven't taken placement test can access starter level courses
        if (!$user->hasCompletedPlacementTest()) {
            return $course->level === 'starter';
        }
        
        // Check if course level is in user's unlocked levels or is their current level
        return $user->hasAccessToLevel($course->level);
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
        
        // Check if course is accessible
        if (!$this->isCourseAccessible) {
            session()->flash('error', 'You do not have access to this course level. Complete previous courses to unlock this content.');
            return;
        }
        
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
