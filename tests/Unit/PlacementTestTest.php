<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\PlacementTest;
use App\Models\PlacementTestQuestion;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestAnswer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PlacementTestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_placement_test()
    {
        $placementTest = PlacementTest::factory()->create([
            'title' => 'English Placement Test',
            'level_mapping' => [
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ]
        ]);

        $this->assertDatabaseHas('placement_tests', [
            'title' => 'English Placement Test'
        ]);

        $this->assertIsArray($placementTest->level_mapping);
        $this->assertCount(5, $placementTest->level_mapping);
    }

    /** @test */
    public function it_can_create_a_placement_test_question()
    {
        $placementTest = PlacementTest::factory()->create();
        
        $question = PlacementTestQuestion::factory()->create([
            'placement_test_id' => $placementTest->id,
            'question_text' => 'What is the capital of France?',
            'options' => [
                'A' => 'London',
                'B' => 'Berlin',
                'C' => 'Paris',
                'D' => 'Madrid'
            ],
            'correct_answer' => 'C'
        ]);

        $this->assertDatabaseHas('placement_test_questions', [
            'placement_test_id' => $placementTest->id,
            'question_text' => 'What is the capital of France?'
        ]);

        $this->assertEquals('C', $question->correct_answer);
    }

    /** @test */
    public function it_can_create_a_placement_test_attempt()
    {
        $user = User::factory()->create();
        $placementTest = PlacementTest::factory()->create();

        $attempt = PlacementTestAttempt::factory()->create([
            'user_id' => $user->id,
            'placement_test_id' => $placementTest->id,
            'score' => 85,
            'assigned_level' => 'intermediate',
            'status' => 'completed'
        ]);

        $this->assertDatabaseHas('placement_test_attempts', [
            'user_id' => $user->id,
            'placement_test_id' => $placementTest->id,
            'score' => 85,
            'assigned_level' => 'intermediate',
            'status' => 'completed'
        ]);
    }

    /** @test */
    public function it_can_create_a_placement_test_answer()
    {
        $user = User::factory()->create();
        $placementTest = PlacementTest::factory()->create();
        $attempt = PlacementTestAttempt::factory()->create([
            'user_id' => $user->id,
            'placement_test_id' => $placementTest->id
        ]);
        $question = PlacementTestQuestion::factory()->create([
            'placement_test_id' => $placementTest->id
        ]);

        $answer = PlacementTestAnswer::factory()->create([
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_answer' => 'B',
            'is_correct' => true,
            'score_awarded' => 1
        ]);

        $this->assertDatabaseHas('placement_test_answers', [
            'attempt_id' => $attempt->id,
            'question_id' => $question->id,
            'selected_answer' => 'B',
            'is_correct' => true,
            'score_awarded' => 1
        ]);
    }

    /** @test */
    public function placement_test_has_questions()
    {
        $placementTest = PlacementTest::factory()->create();
        PlacementTestQuestion::factory()->count(5)->create([
            'placement_test_id' => $placementTest->id
        ]);

        $this->assertCount(5, $placementTest->questions);
    }

    /** @test */
    public function placement_test_has_attempts()
    {
        $user = User::factory()->create();
        $placementTest = PlacementTest::factory()->create();
        PlacementTestAttempt::factory()->count(3)->create([
            'placement_test_id' => $placementTest->id,
            'user_id' => $user->id
        ]);

        $this->assertCount(3, $placementTest->attempts);
    }
}
