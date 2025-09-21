<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PlacementTest;

class PlacementTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default placement test
        PlacementTest::factory()->create([
            'title' => 'English Placement Test',
            'description' => 'Assess your English proficiency level to determine the appropriate courses for you.',
            'is_active' => true,
            'level_mapping' => [
                "0-20" => "starter",
                "21-40" => "beginner",
                "41-60" => "elementary",
                "61-80" => "intermediate",
                "81-100" => "advanced"
            ]
        ]);
    }
}
