<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Progress;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_course()
    {
        $user = User::factory()->create();
        
        $course = Course::factory()->create([
            'title' => 'Beginner English Course',
            'level' => 'beginner',
            'created_by' => $user->id
        ]);

        $this->assertDatabaseHas('courses', [
            'title' => 'Beginner English Course',
            'level' => 'beginner',
            'created_by' => $user->id
        ]);
    }

    /** @test */
    public function course_has_lessons()
    {
        $course = Course::factory()->create();
        Lesson::factory()->count(3)->create(['course_id' => $course->id]);

        $this->assertCount(3, $course->lessons);
    }

    /** @test */
    public function course_can_get_user_progress()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        
        // Create lessons for the course
        $lesson1 = Lesson::factory()->create(['course_id' => $course->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id]);
        $lesson3 = Lesson::factory()->create(['course_id' => $course->id]);
        
        // Create progress for some lessons
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
        
        // Get user progress for the course
        $progress = $course->getUserProgress($user->id);
        
        $this->assertEquals(3, $progress['total']);
        $this->assertEquals(2, $progress['completed']);
        $this->assertEquals(66.67, round($progress['percentage'], 2));
        $this->assertFalse($progress['is_completed']);
    }

    /** @test */
    public function course_progress_is_100_percent_when_all_lessons_completed()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        
        // Create lessons for the course
        $lesson1 = Lesson::factory()->create(['course_id' => $course->id]);
        $lesson2 = Lesson::factory()->create(['course_id' => $course->id]);
        
        // Create progress for all lessons
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
        
        // Get user progress for the course
        $progress = $course->getUserProgress($user->id);
        
        $this->assertEquals(2, $progress['total']);
        $this->assertEquals(2, $progress['completed']);
        $this->assertEquals(100, $progress['percentage']);
        $this->assertTrue($progress['is_completed']);
    }

    /** @test */
    public function course_progress_handles_courses_with_no_lessons()
    {
        $user = User::factory()->create();
        $course = Course::factory()->create();
        
        // Get user progress for the course with no lessons
        $progress = $course->getUserProgress($user->id);
        
        $this->assertEquals(0, $progress['total']);
        $this->assertEquals(0, $progress['completed']);
        $this->assertEquals(0, $progress['percentage']);
        $this->assertTrue($progress['is_completed']);
    }

    /** @test */
    public function course_can_be_scoped_by_level()
    {
        Course::factory()->create(['level' => 'starter']);
        Course::factory()->create(['level' => 'beginner']);
        Course::factory()->create(['level' => 'beginner']);
        Course::factory()->create(['level' => 'intermediate']);

        $beginnerCourses = Course::byLevel('beginner')->get();
        
        $this->assertCount(2, $beginnerCourses);
        $this->assertTrue($beginnerCourses->every(function ($course) {
            return $course->level === 'beginner';
        }));
    }

    /** @test */
    public function course_can_be_scoped_as_active()
    {
        Course::factory()->create(['is_trial' => true, 'published_at' => now()]);
        Course::factory()->create(['is_trial' => true, 'published_at' => null]); // Not published
        Course::factory()->create(['is_trial' => false, 'published_at' => now()]); // Not trial

        $activeCourses = Course::active()->get();
        
        $this->assertCount(1, $activeCourses);
        $this->assertTrue($activeCourses->every(function ($course) {
            return $course->is_trial === true && $course->published_at !== null;
        }));
    }
}