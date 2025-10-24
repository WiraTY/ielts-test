<?php

namespace App\Livewire;

use App\Models\Course;
use Livewire\Component;

class CourseList extends Component
{
    public function render()
    {
        // Get courses that are trials and published
        $query = Course::where('is_trial', true)
            ->whereNotNull('published_at')
            ->orderBy('order');
        
        // Eager load lessons count
        $query->withCount('lessons');
        
        $courses = $query->get();

        // Mark which courses are accessible based on user's level
        if (auth()->check()) {
            $user = auth()->user();
            
            foreach ($courses as $course) {
                // Check if course is accessible based on user's level
                $course->is_accessible = $user->can('view', $course);
                
                // Calculate progress for accessible courses
                if ($course->is_accessible) {
                    $progress = $course->getUserProgress(auth()->id());
                    $course->is_completed = $progress['is_completed'];
                    $course->progress_percentage = $progress['percentage'];
                }
            }
        } else {
            // For guests, mark starter courses as accessible (but they'll need to login to access)
            foreach ($courses as $course) {
                $course->is_accessible = ($course->level === 'starter');
            }
        }

        return view('livewire.course-list', compact('courses'));
    }
}