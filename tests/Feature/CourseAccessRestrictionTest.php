<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseAccessRestrictionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function student_can_only_see_courses_at_their_level()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create courses at different levels
        $starterCourse = Course::factory()->create([
            'level' => 'starter',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        $beginnerCourse = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        $intermediateCourse = Course::factory()->create([
            'level' => 'intermediate',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // View course list
        $response = $this->actingAs($user)->get(route('courses.index'));
        
        $response->assertStatus(200);
        $response->assertSee($starterCourse->title); // Should see starter course
        $response->assertSee($beginnerCourse->title); // Should see beginner course
        $response->assertDontSee($intermediateCourse->title); // Should NOT see intermediate course
    }

    /** @test */
    public function student_cannot_access_course_detail_of_higher_level()
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
        
        // Try to access the course detail
        $response = $this->actingAs($user)->get(route('courses.show', $intermediateCourse));
        
        // Should be forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function student_can_access_course_detail_at_their_level()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create a course at the user's level
        $beginnerCourse = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // Try to access the course detail
        $response = $this->actingAs($user)->get(route('courses.show', $beginnerCourse));
        
        // Should be successful
        $response->assertStatus(200);
    }

    /** @test */
    public function student_can_access_course_detail_at_lower_unlocked_level()
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
        
        // Try to access the course detail
        $response = $this->actingAs($user)->get(route('courses.show', $beginnerCourse));
        
        // Should be successful
        $response->assertStatus(200);
    }

    /** @test */
    public function student_cannot_enroll_in_course_of_higher_level()
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
        
        $lesson = Lesson::factory()->create(['course_id' => $intermediateCourse->id]);
        
        // Try to enroll in the course (access first lesson)
        $response = $this->actingAs($user)->get(route('lessons.show', [
            'course' => $intermediateCourse->slug,
            'lesson' => $lesson->slug
        ]));
        
        // Should be forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function new_student_without_placement_test_can_only_access_starter_courses()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'has_taken_placement_test' => false,
            'current_level' => null,
            'unlocked_levels' => null
        ]);
        
        // Create courses at different levels
        $starterCourse = Course::factory()->create([
            'level' => 'starter',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        $beginnerCourse = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // View course list
        $response = $this->actingAs($user)->get(route('courses.index'));
        
        $response->assertStatus(200);
        $response->assertSee($starterCourse->title); // Should see starter course
        $response->assertDontSee($beginnerCourse->title); // Should NOT see beginner course
    }
}