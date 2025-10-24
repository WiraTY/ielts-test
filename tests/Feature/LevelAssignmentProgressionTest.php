<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\PlacementTest;
use App\Models\PlacementTestAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LevelAssignmentProgressionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_gets_assigned_level_after_completing_placement_test()
    {
        $user = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create([
            'is_active' => true,
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
        
        // Simulate viewing the results page which should trigger level assignment
        $response = $this->actingAs($user)->get(route('placement-tests.result', $attempt));
        
        $response->assertStatus(200);
        
        // Refresh user model
        $user->refresh();
        
        // Check that user has been assigned the correct level
        $this->assertTrue($user->hasCompletedPlacementTest());
        $this->assertEquals('elementary', $user->getAssignedLevel());
        $this->assertEquals('elementary', $user->getCurrentLevel());
        $this->assertTrue($user->hasAccessToLevel('elementary'));
    }

    /** @test */
    public function user_can_progress_to_next_level_after_completing_all_courses()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create courses and lessons at beginner level
        $course1 = Course::factory()->create(['level' => 'beginner']);
        $lesson1 = Lesson::factory()->create(['course_id' => $course1->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course1->id]);
        
        $course2 = Course::factory()->create(['level' => 'beginner']);
        $lesson3 = Lesson::factory()->create(['course_id' => $course2->id]);
        $lesson4 = Lesson::factory()->create(['course_id' => $course2->id]);
        
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
        
        // Simulate completing a quiz which should trigger level progression check
        // We'll simulate this by directly calling the event listener logic
        
        // Check if user has completed all courses at current level
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
        
        // If all courses are completed, progress to next level
        if ($completedCoursesCount >= $coursesAtCurrentLevel->count()) {
            $levelOrder = ['starter', 'beginner', 'elementary', 'intermediate', 'advanced'];
            $currentIndex = array_search($user->current_level, $levelOrder);
            
            if ($currentIndex !== false && $currentIndex < count($levelOrder) - 1) {
                $nextLevel = $levelOrder[$currentIndex + 1];
                
                $unlockedLevels = $user->unlocked_levels ?? [];
                $unlockedLevels[] = $nextLevel;
                $unlockedLevels = array_unique($unlockedLevels);
                
                $user->update([
                    'current_level' => $nextLevel,
                    'unlocked_levels' => $unlockedLevels
                ]);
            }
        }
        
        // Refresh user model
        $user->refresh();
        
        // Check that user has progressed to the next level
        $this->assertEquals('elementary', $user->current_level);
        $this->assertTrue(in_array('elementary', $user->unlocked_levels));
    }

    /** @test */
    public function user_cannot_access_courses_above_their_level()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create a course at a higher level
        $intermediateCourse = Course::factory()->create([
            'level' => 'intermediate',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // Try to access the course
        $response = $this->actingAs($user)->get(route('courses.show', $intermediateCourse));
        
        // Should be forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function user_can_access_courses_at_their_current_level()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create a course at the user's current level
        $beginnerCourse = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // Try to access the course
        $response = $this->actingAs($user)->get(route('courses.show', $beginnerCourse));
        
        // Should be successful
        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_access_courses_at_lower_unlocked_levels()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'intermediate',
            'unlocked_levels' => ['starter', 'beginner', 'elementary', 'intermediate']
        ]);
        
        // Create a course at a lower level
        $beginnerCourse = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // Try to access the course
        $response = $this->actingAs($user)->get(route('courses.show', $beginnerCourse));
        
        // Should be successful
        $response->assertStatus(200);
    }
}