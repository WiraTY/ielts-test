<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Services\LevelProgressionService;
use App\Services\LevelAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LevelProgressionServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_check_if_user_has_completed_current_level()
    {
        $levelAssignmentService = new LevelAssignmentService();
        $service = new LevelProgressionService($levelAssignmentService);
        
        $user = User::factory()->create([
            'current_level' => 'beginner'
        ]);
        
        // Create courses at beginner level
        $course1 = Course::factory()->create(['level' => 'beginner']);
        $lesson1 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course1->id]);
        
        $course2 = Course::factory()->create(['level' => 'beginner']);
        $lesson3 = Lesson::factory()->create(['course_id' => $course2->id]);
        $lesson4 = Lesson::factory()->create(['course_id' => $course2->id]);
        
        // Initially, user has not completed any lessons
        $this->assertFalse($service->hasUserCompletedCurrentLevel($user));
        
        // Complete some lessons
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson2->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // User still hasn't completed all lessons
        $this->assertFalse($service->hasUserCompletedCurrentLevel($user));
        
        // Complete remaining lessons
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson3->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson4->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // Refresh user model
        $user->refresh();
        
        // Now user should have completed current level
        $this->assertTrue($service->hasUserCompletedCurrentLevel($user));
    }

    /** @test */
    public function it_can_advance_user_to_next_level()
    {
        $levelAssignmentService = new LevelAssignmentService();
        $service = new LevelProgressionService($levelAssignmentService);
        
        $user = User::factory()->create([
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create courses at beginner level
        $course1 = Course::factory()->create(['level' => 'beginner']);
        $lesson1 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course1->id]);
        
        // Complete all lessons at beginner level
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson2->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // Refresh user model
        $user->refresh();
        
        // Advance user to next level
        $result = $service->advanceUserToNextLevel($user);
        
        // Check that advancement was successful
        $this->assertTrue($result);
        
        // Refresh user model again
        $user->refresh();
        
        // Check that user is now at elementary level
        $this->assertEquals('elementary', $user->current_level);
        $this->assertContains('elementary', $user->unlocked_levels);
    }

    /** @test */
    public function it_can_get_current_level_completion_percentage()
    {
        $levelAssignmentService = new LevelAssignmentService();
        $service = new LevelProgressionService($levelAssignmentService);
        
        $user = User::factory()->create([
            'current_level' => 'beginner'
        ]);
        
        // Create courses at beginner level
        $course1 = Course::factory()->create(['level' => 'beginner']);
        $lesson1 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson3 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson4 = Lesson::factory()->create(['course_id' => $course1->id]);
        
        // Complete half of the lessons
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson2->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // Get completion percentage
        $percentage = $service->getCurrentLevelCompletionPercentage($user);
        
        // Should be 50% (2 out of 4 lessons completed)
        $this->assertEquals(50.0, $percentage);
    }

    /** @test */
    public function it_can_get_level_progress_stats()
    {
        $levelAssignmentService = new LevelAssignmentService();
        $service = new LevelProgressionService($levelAssignmentService);
        
        $user = User::factory()->create([
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create courses at beginner level
        $course1 = Course::factory()->create(['level' => 'beginner']);
        $lesson1 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course1->id]);
        
        // Complete all lessons
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson2->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // Get progress stats
        $stats = $service->getLevelProgressStats($user);
        
        $this->assertEquals('beginner', $stats['current_level']);
        $this->assertEquals(['starter', 'beginner'], $stats['unlocked_levels']);
        $this->assertEquals(100.0, $stats['completion_percentage']);
        $this->assertTrue($stats['can_advance']);
        $this->assertEquals('elementary', $stats['next_level']);
    }
}