<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\PlacementTest;
use App\Services\LevelAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LevelAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_determine_level_from_score()
    {
        $service = new LevelAssignmentService();
        
        $placementTest = PlacementTest::factory()->create([
            'level_mapping' => [
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ]
        ]);

        $this->assertEquals('starter', $service->determineLevelFromScore($placementTest, 15));
        $this->assertEquals('beginner', $service->determineLevelFromScore($placementTest, 35));
        $this->assertEquals('elementary', $service->determineLevelFromScore($placementTest, 50));
        $this->assertEquals('intermediate', $service->determineLevelFromScore($placementTest, 75));
        $this->assertEquals('advanced', $service->determineLevelFromScore($placementTest, 95));
    }

    /** @test */
    public function it_returns_starter_level_for_scores_outside_mapping()
    {
        $service = new LevelAssignmentService();
        
        $placementTest = PlacementTest::factory()->create([
            'level_mapping' => [
                "20-80" => "intermediate"
            ]
        ]);

        // Scores outside the mapping range should return starter level
        $this->assertEquals('starter', $service->determineLevelFromScore($placementTest, 10));
        $this->assertEquals('starter', $service->determineLevelFromScore($placementTest, 90));
    }

    /** @test */
    public function it_can_assign_level_to_user()
    {
        $service = new LevelAssignmentService();
        
        $user = User::factory()->create([
            'has_taken_placement_test' => false,
            'assigned_level' => null,
            'current_level' => null,
            'unlocked_levels' => null
        ]);
        
        $placementTest = PlacementTest::factory()->create([
            'level_mapping' => [
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ]
        ]);

        $service->assignLevelToUser($user, $placementTest, 50);
        
        $user->refresh();
        
        $this->assertTrue($user->has_taken_placement_test);
        $this->assertEquals('elementary', $user->assigned_level);
        $this->assertEquals('elementary', $user->current_level);
        $this->assertEquals(['elementary'], $user->unlocked_levels);
    }

    /** @test */
    public function it_can_unlock_level_for_user()
    {
        $service = new LevelAssignmentService();
        
        $user = User::factory()->create([
            'unlocked_levels' => ['starter', 'beginner']
        ]);

        $service->unlockLevelForUser($user, 'elementary');
        
        $user->refresh();
        
        $this->assertContains('starter', $user->unlocked_levels);
        $this->assertContains('beginner', $user->unlocked_levels);
        $this->assertContains('elementary', $user->unlocked_levels);
    }

    /** @test */
    public function it_can_get_next_level()
    {
        $service = new LevelAssignmentService();
        
        $this->assertEquals('beginner', $service->getNextLevel('starter'));
        $this->assertEquals('elementary', $service->getNextLevel('beginner'));
        $this->assertEquals('intermediate', $service->getNextLevel('elementary'));
        $this->assertEquals('advanced', $service->getNextLevel('intermediate'));
        $this->assertNull($service->getNextLevel('advanced')); // No next level
    }
}