<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\PlacementTest;
use App\Models\PlacementTestQuestion;
use App\Models\PlacementTestAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EndToEndUserFlowTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function complete_user_journey_from_registration_to_level_progression()
    {
        // 1. User registration
        $response = $this->post('/register', [
            'name' => 'Test Student',
            'email' => 'student@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);
        
        $response->assertStatus(302); // Redirect after registration
        
        // Get the created user
        $user = User::where('email', 'student@test.com')->first();
        $this->assertNotNull($user);
        
        // 2. Login
        $response = $this->post('/login', [
            'email' => 'student@test.com',
            'password' => 'password123'
        ]);
        
        $response->assertStatus(302); // Redirect after login
        $this->assertAuthenticatedAs($user);
        
        // 3. View dashboard (should show placement test prompt)
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        
        // 4. Take placement test
        $placementTest = PlacementTest::factory()->create([
            'is_active' => true,
            'level_mapping' => [
                "0-100" => "beginner"
            ]
        ]);
        
        // Create questions for the test
        $question = PlacementTestQuestion::factory()->create([
            'placement_test_id' => $placementTest->id,
            'options' => [
                'A' => 'Option A',
                'B' => 'Option B',
                'C' => 'Option C',
                'D' => 'Option D'
            ],
            'correct_answer' => 'A'
        ]);
        
        // Start the test
        $response = $this->get(route('placement-tests.start', $placementTest));
        $response->assertStatus(200);
        
        // Submit the test
        $response = $this->post(route('placement-tests.submit', $placementTest), [
            'answers' => [
                $question->id => ['option' => 'A']
            ]
        ]);
        
        $response->assertStatus(302); // Redirect to results
        
        // Check that user has been assigned a level
        $user->refresh();
        $this->assertTrue($user->hasCompletedPlacementTest());
        $this->assertEquals('beginner', $user->getAssignedLevel());
        
        // 5. View courses available at assigned level
        $response = $this->get('/courses');
        $response->assertStatus(200);
        
        // 6. Enroll in a course
        $course = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        $lesson = Lesson::factory()->create(['course_id' => $course->id]);
        
        // Access the lesson
        $response = $this->get(route('lessons.show', [
            'course' => $course->slug,
            'lesson' => $lesson->slug
        ]));
        
        $response->assertStatus(200);
        
        // 7. Complete the lesson (mark as completed)
        Progress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'status' => 'completed',
            'completed_at' => now()
        ]);
        
        // 8. View dashboard to see progress
        $response = $this->get('/dashboard');
        $response->assertStatus(200);
        
        // Check that progress is shown
        $response->assertSee($course->title);
    }

    /** @test */
    public function admin_user_journey_for_managing_courses_and_placement_tests()
    {
        // 1. Admin login
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        
        // 2. Create a placement test
        $response = $this->actingAs($admin)->post(route('admin.placement-tests.store'), [
            'title' => 'English Placement Test',
            'description' => 'Test for new students',
            'duration_minutes' => 30,
            'is_active' => true
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('placement_tests', ['title' => 'English Placement Test']);
        
        // 3. Create a course
        $response = $this->actingAs($admin)->post(route('admin.courses.store'), [
            'title' => 'Beginner English Course',
            'description' => 'Course for beginners',
            'is_trial' => true,
            'status' => 'published',
            'level' => 'beginner'
        ]);
        
        $response->assertStatus(302);
        $this->assertDatabaseHas('courses', ['title' => 'Beginner English Course']);
        
        // 4. View placement test reports
        $response = $this->actingAs($admin)->get(route('admin.placement-tests.reports'));
        $response->assertStatus(200);
        $response->assertSee('Placement Test Reports');
        
        // 5. View user level tracking
        $response = $this->actingAs($admin)->get(route('admin.user-level-tracking'));
        $response->assertStatus(200);
        $response->assertSee('User Level Tracking');
    }
}
