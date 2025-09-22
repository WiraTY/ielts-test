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

        // If user is authenticated, mark which courses are accessible
        if (auth()->check()) {
            $user = auth()->user();
            $unlockedLevels = $user->unlocked_levels ?? [];
            $currentLevel = $user->current_level ?? 'starter';
            
            // Include courses from current level and unlocked levels
            $allowedLevels = array_unique(array_merge($unlockedLevels, [$currentLevel]));
            
            foreach ($courses as $course) {
                // Check if course is accessible
                $course->is_accessible = in_array($course->level, $allowedLevels);
                
                // Calculate progress for accessible courses
                if ($course->is_accessible) {
                    $progress = $course->getUserProgress(auth()->id());
                    $course->is_completed = $progress['is_completed'];
                    $course->progress_percentage = $progress['percentage'];
                }
            }
        } else {
            // For guests, mark all courses as accessible (but they'll need to login to access)
            foreach ($courses as $course) {
                $course->is_accessible = true;
            }
        }

        return view('livewire.course-list', compact('courses'));
    }
}