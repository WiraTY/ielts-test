<?php

namespace App\Listeners;

use App\Events\CourseCompleted;
use App\Services\LevelProgressionService;
use App\Services\LevelAssignmentService;
use Illuminate\Support\Facades\Log;

class CheckLevelProgression
{
    protected LevelProgressionService $levelProgressionService;
    
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $levelAssignmentService = new LevelAssignmentService();
        $this->levelProgressionService = new LevelProgressionService($levelAssignmentService);
    }

    /**
     * Handle the event.
     */
    public function handle(CourseCompleted $event): void
    {
        $user = $event->user;
        $completedCourse = $event->course;
        
        // Only check progression if the completed course is at the user's current level
        if ($completedCourse->level !== $user->current_level) {
            return;
        }
        
        // Check if user has completed all courses at current level and advance if possible
        $this->levelProgressionService->advanceUserToNextLevel($user);
        
        // Log the level progression check
        Log::info('Checked level progression for user', [
            'user_id' => $user->id,
            'completed_course_id' => $completedCourse->id,
            'completed_course_level' => $completedCourse->level
        ]);
    }
}
