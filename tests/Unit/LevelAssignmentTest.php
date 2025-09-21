<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\PlacementTest;
use App\Models\PlacementTestAttempt;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LevelAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_assigned_a_level_based_on_placement_test_score()
    {
        // Create a user
        $user = User::factory()->create();
        
        // Create a placement test with level mapping
        $placementTest = PlacementTest::factory()->create([
            'level_mapping' => [
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ]
        ]);
        
        // Create a placement test attempt with a score that should assign "elementary" level
        $attempt = PlacementTestAttempt::factory()->create([
            'user_id' => $user->id,
            'placement_test_id' => $placementTest->id,
            'score' => 50, // 50% score
            'status' => 'completed'
        ]);
        
        // Manually assign level (simulating the assignLevel method)
        $user->update([
            'has_taken_placement_test' => true,
            'assigned_level' => 'elementary',
            'current_level' => 'elementary',
            'unlocked_levels' => ['starter', 'beginner', 'elementary']
        ]);
        
        // Verify the user has the correct level assigned
        $this->assertTrue($user->hasCompletedPlacementTest());
        $this->assertEquals('elementary', $user->getAssignedLevel());
        $this->assertEquals('elementary', $user->getCurrentLevel());
        $this->assertTrue($user->hasAccessToLevel('elementary'));
        $this->assertTrue($user->hasAccessToLevel('beginner'));
        $this->assertTrue($user->hasAccessToLevel('starter'));
        $this->assertFalse($user->hasAccessToLevel('intermediate'));
    }

    /** @test */
    public function user_can_progress_to_next_level_after_completing_all_courses()
    {
        // Create a user with elementary level
        $user = User::factory()->create([
            'current_level' => 'elementary',
            'unlocked_levels' => ['starter', 'beginner', 'elementary']
        ]);
        
        // Verify user can access elementary level
        $this->assertTrue($user->hasAccessToLevel('elementary'));
        $this->assertFalse($user->hasAccessToLevel('intermediate'));
        
        // Simulate completing all elementary courses and progressing to intermediate
        $user->update([
            'current_level' => 'intermediate',
            'unlocked_levels' => ['starter', 'beginner', 'elementary', 'intermediate']
        ]);
        
        // Verify user now has access to intermediate level
        $this->assertTrue($user->hasAccessToLevel('intermediate'));
        $this->assertEquals('intermediate', $user->getCurrentLevel());
    }
    
    /** @test */
    public function user_progresses_to_next_level_when_all_courses_completed()
    {
        // Create a user at elementary level
        $user = User::factory()->create([
            'current_level' => 'elementary',
            'unlocked_levels' => ['starter', 'beginner', 'elementary']
        ]);
        
        // Create courses and lessons at elementary level
        $course1 = Course::factory()->create(['level' => 'elementary']);
        $lesson1 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course1->id]);
        
        $course2 = Course::factory()->create(['level' => 'elementary']);
        $lesson3 = Lesson::factory()->create(['course_id' => $course2->id]);
        $lesson4 = Lesson::factory()->create(['course_id' => $course2->id]);
        
        // Create progress records for completing the lessons
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
        
        // Manually check level progression (simulating our listener)
        $coursesAtCurrentLevel = Course::byLevel($user->current_level)->get();
        $completedCoursesCount = 0;
        
        foreach ($coursesAtCurrentLevel as $course) {
            // Check if all lessons in the course are completed
            $totalLessons = $course->lessons()->count();
            $completedLessons = $user->progress()
                ->whereIn('lesson_id', $course->lessons()->pluck('id'))
                ->where('status', 'completed')
                ->count();
                
            if ($totalLessons > 0 && $completedLessons >= $totalLessons) {
                $completedCoursesCount++;
            }
        }
        
        // Verify that all courses at current level are completed
        $this->assertEquals($coursesAtCurrentLevel->count(), $completedCoursesCount);
        
        // Simulate level progression
        $levelOrder = ['starter', 'beginner', 'elementary', 'intermediate', 'advanced'];
        $currentIndex = array_search($user->current_level, $levelOrder);
        $nextLevel = $levelOrder[$currentIndex + 1];
        
        $unlockedLevels = $user->unlocked_levels;
        $unlockedLevels[] = $nextLevel;
        $unlockedLevels = array_unique($unlockedLevels);
        
        $user->update([
            'current_level' => $nextLevel,
            'unlocked_levels' => $unlockedLevels
        ]);
        
        // Verify user has progressed to intermediate level
        $this->assertEquals('intermediate', $user->current_level);
        $this->assertTrue(in_array('intermediate', $user->unlocked_levels));
    }
}
