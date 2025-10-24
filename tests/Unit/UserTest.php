<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_be_created()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student'
        ]);
    }

    /** @test */
    public function user_can_be_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->assertTrue($admin->isAdmin());
    }

    /** @test */
    public function user_can_be_student()
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->assertFalse($student->isAdmin());
    }

    /** @test */
    public function user_has_placement_test_attempts()
    {
        $user = User::factory()->create();
        
        // Create placement test attempts for the user
        \App\Models\PlacementTestAttempt::factory()->count(2)->create(['user_id' => $user->id]);

        $this->assertCount(2, $user->placementTestAttempts);
    }

    /** @test */
    public function user_has_progress()
    {
        $user = User::factory()->create();
        
        // Create progress records for the user
        Progress::factory()->count(3)->create(['user_id' => $user->id]);

        $this->assertCount(3, $user->progress);
    }

    /** @test */
    public function user_can_have_access_to_specific_level()
    {
        $user = User::factory()->create([
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);

        $this->assertTrue($user->hasAccessToLevel('beginner'));
        $this->assertTrue($user->hasAccessToLevel('starter'));
        $this->assertFalse($user->hasAccessToLevel('intermediate'));
    }

    /** @test */
    public function user_can_check_if_has_completed_placement_test()
    {
        $user1 = User::factory()->create(['has_taken_placement_test' => true]);
        $user2 = User::factory()->create(['has_taken_placement_test' => false]);

        $this->assertTrue($user1->hasCompletedPlacementTest());
        $this->assertFalse($user2->hasCompletedPlacementTest());
    }

    /** @test */
    public function user_can_get_assigned_level()
    {
        $user1 = User::factory()->create(['assigned_level' => 'intermediate']);
        $user2 = User::factory()->create(['assigned_level' => null]);

        $this->assertEquals('intermediate', $user1->getAssignedLevel());
        $this->assertEquals('starter', $user2->getAssignedLevel()); // Default level
    }

    /** @test */
    public function user_can_get_current_level()
    {
        $user1 = User::factory()->create(['current_level' => 'advanced']);
        $user2 = User::factory()->create(['current_level' => null]);

        $this->assertEquals('advanced', $user1->getCurrentLevel());
        $this->assertEquals('starter', $user2->getCurrentLevel()); // Default level
    }

    /** @test */
    public function user_can_check_if_has_completed_course()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        
        // Create lessons for the course
        $lesson1 = Lesson::factory()->create(['course_id' => $course->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id]);
        
        // Create progress for only one lesson
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson1->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // User should not have completed the course yet
        $this->assertFalse($user->hasCompletedCourse($course));
        
        // Create progress for the second lesson
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson2->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // Refresh the user model
        $user->refresh();
        
        // User should now have completed the course
        $this->assertTrue($user->hasCompletedCourse($course));
    }
}