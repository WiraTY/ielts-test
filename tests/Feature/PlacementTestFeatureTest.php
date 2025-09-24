<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\PlacementTest;
use App\Models\PlacementTestQuestion;
use App\Models\PlacementTestAttempt;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class PlacementTestFeatureTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function student_can_view_placement_test_list()
    {
        $student = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create(['is_active' => true]);
        
        $response = $this->actingAs($student)->get(route('placement-tests.index'));
        
        $response->assertStatus(200);
        $response->assertSee($placementTest->title);
    }

    /** @test */
    public function student_can_view_placement_test_details()
    {
        $student = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create(['is_active' => true]);
        
        $response = $this->actingAs($student)->get(route('placement-tests.show', $placementTest));
        
        $response->assertStatus(200);
        $response->assertSee($placementTest->title);
    }

    /** @test */
    public function student_can_start_placement_test()
    {
        $student = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create(['is_active' => true]);
        
        $response = $this->actingAs($student)->get(route('placement-tests.start', $placementTest));
        
        $response->assertStatus(200);
        $response->assertSee('Start Placement Test');
    }

    /** @test */
    public function student_can_submit_placement_test()
    {
        $student = User::factory()->create(['role' => 'student']);
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
        
        $response = $this->actingAs($student)->post(route('placement-tests.submit', $placementTest), [
            'answers' => [
                $question->id => ['option' => 'A']
            ]
        ]);
        
        $response->assertStatus(302); // Redirect to results page
        
        // Check that attempt was created
        $this->assertDatabaseHas('placement_test_attempts', [
            'user_id' => $student->id,
            'placement_test_id' => $placementTest->id,
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function student_can_view_placement_test_results()
    {
        $student = User::factory()->create(['role' => 'student']);
        $placementTest = PlacementTest::factory()->create(['is_active' => true]);
        $attempt = PlacementTestAttempt::factory()->create([
            'user_id' => $student->id,
            'placement_test_id' => $placementTest->id,
            'status' => 'completed'
        ]);
        
        $response = $this->actingAs($student)->get(route('placement-tests.result', $attempt));
        
        $response->assertStatus(200);
        $response->assertSee('Test Results');
    }

    /** @test */
    public function admin_can_create_placement_test()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        
        $response = $this->actingAs($admin)->post(route('admin.placement-tests.store'), [
            'title' => 'New Placement Test',
            'description' => 'A test for new students',
            'duration_minutes' => 30,
            'is_active' => true
        ]);
        
        $response->assertStatus(302); // Redirect to edit page
        $this->assertDatabaseHas('placement_tests', [
            'title' => 'New Placement Test'
        ]);
    }

    /** @test */
    public function admin_can_edit_placement_test()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        $response = $this->actingAs($admin)->put(route('admin.placement-tests.update', $placementTest), [
            'title' => 'Updated Placement Test',
            'description' => 'An updated test',
            'duration_minutes' => 45,
            'is_active' => false
        ]);
        
        $response->assertStatus(302); // Redirect back to edit page
        $this->assertDatabaseHas('placement_tests', [
            'title' => 'Updated Placement Test',
            'description' => 'An updated test',
            'duration_minutes' => 45,
            'is_active' => false
        ]);
    }

    /** @test */
    public function admin_can_delete_placement_test()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        $response = $this->actingAs($admin)->delete(route('admin.placement-tests.destroy', $placementTest));
        
        $response->assertStatus(302); // Redirect to index page
        $this->assertDatabaseMissing('placement_tests', [
            'id' => $placementTest->id
        ]);
    }

    /** @test */
    public function admin_can_view_placement_test_reports()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $placementTest = PlacementTest::factory()->create();
        
        $response = $this->actingAs($admin)->get(route('admin.placement-tests.reports'));
        
        $response->assertStatus(200);
        $response->assertSee('Placement Test Reports');
    }
}
