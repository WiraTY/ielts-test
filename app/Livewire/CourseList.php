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
            
        // If user is authenticated, filter courses by their unlocked levels
        if (auth()->check()) {
            $user = auth()->user();
            $unlockedLevels = $user->unlocked_levels ?? [];
            $currentLevel = $user->current_level ?? 'starter';
            
            // Include courses from current level and unlocked levels
            $allowedLevels = array_unique(array_merge($unlockedLevels, [$currentLevel]));
            
            $query->whereIn('level', $allowedLevels);
        }
        
        $courses = $query->get();

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