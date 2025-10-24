<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Course;
use App\Models\PlacementTest;
use App\Models\PlacementTestQuestion;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

class EdgeCaseAndErrorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_cannot_take_placement_test_if_already_taken()
    {
        $user = User::factory()->create(['role' => 'student', 'has_taken_placement_test' => true]);
        $placementTest = PlacementTest::factory()->create(['is_active' => true]);
        
        // Try to access the placement test start page
        $response = $this->actingAs($user)->get(route('placement-tests.start', $placementTest));
        
        // Should be redirected or show error
        $response->assertStatus(403); // Or redirect to dashboard
    }

    /** @test */
    public function inactive_placement_test_cannot_be_accessed()
    {
        $user = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create(['is_active' => false]);
        
        // Try to access the placement test start page
        $response = $this->actingAs($user)->get(route('placement-tests.start', $placementTest));
        
        // Should be forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function user_cannot_submit_placement_test_with_invalid_answers()
    {
        $user = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create(['is_active' => true]);
        
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
        
        // Submit with invalid answer option
        $response = $this->actingAs($user)->post(route('placement-tests.submit', $placementTest), [
            'answers' => [
                $question->id => ['option' => 'E'] // Invalid option
            ]
        ]);
        
        // Should handle gracefully (might not be an error depending on implementation)
        $response->assertStatus(302); // Redirect
    }

    /** @test */
    public function course_without_lessons_handled_properly()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create a course without lessons
        $course = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => now()
        ]);
        
        // Try to access the course
        $response = $this->actingAs($user)->get(route('courses.show', $course));
        
        // Should be successful
        $response->assertStatus(200);
    }

    /** @test */
    public function user_cannot_access_non_existent_course()
    {
        $user = User::factory()->create(['role' => 'student']);
        
        // Try to access a non-existent course
        $response = $this->actingAs($user)->get('/courses/non-existent-course');
        
        // Should be 404
        $response->assertStatus(404);
    }

    /** @test */
    public function admin_cannot_delete_placement_test_with_attempts()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        // Create an attempt for the test
        \App\Models\PlacementTestAttempt::factory()->create([
            'placement_test_id' => $placementTest->id
        ]);
        
        // Try to delete the placement test
        $response = $this->actingAs($admin)->delete(route('admin.placement-tests.destroy', $placementTest));
        
        // Should fail with error message
        $response->assertStatus(302); // Redirect back
        $response->assertSessionHas('error');
    }

    /** @test */
    public function excel_import_handles_malformed_files()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        // Create a fake malformed file
        $file = UploadedFile::fake()->create('malformed.xlsx', 100);
        
        // Try to import
        $response = $this->actingAs($admin)->post(route('admin.placement-tests.import-questions', $placementTest), [
            'excel_file' => $file
        ]);
        
        // Should handle gracefully
        $response->assertStatus(302); // Redirect back
        // Might have error message in session
    }

    /** @test */
    public function user_cannot_access_admin_routes()
    {
        $student = User::factory()->create(['role' => 'student']);
        
        // Try to access admin dashboard
        $response = $this->actingAs($student)->get('/admin/dashboard');
        
        // Should be forbidden
        $response->assertStatus(403);
    }

    /** @test */
    public function guest_cannot_access_protected_routes()
    {
        // Try to access dashboard
        $response = $this->get('/dashboard');
        
        // Should redirect to login
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }

    /** @test */
    public function user_cannot_access_course_with_unpublished_lessons()
    {
        $user = User::factory()->create([
            'role' => 'student',
            'current_level' => 'beginner',
            'unlocked_levels' => ['starter', 'beginner']
        ]);
        
        // Create a course with unpublished lessons
        $course = Course::factory()->create([
            'level' => 'beginner',
            'is_trial' => true,
            'published_at' => null // Not published
        ]);
        
        // Try to access the course
        $response = $this->actingAs($user)->get(route('courses.show', $course));
        
        // Should be forbidden
        $response->assertStatus(403);
    }
}