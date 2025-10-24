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
        // Create a default placement test using Pearson GSE scale
        PlacementTest::factory()->create([
            'title' => 'English Placement Test',
            'description' => 'Assess your English proficiency level using the Pearson Global Scale of English (GSE) to determine the appropriate courses for you.',
            'is_active' => true,
            'level_mapping' => [
                "22-35" => "starter",              // GSE 22-35: Starter (A1-A1+)
                "30-42" => "elementary",           // GSE 30-42: Elementary (A1+-A2)
                "36-46" => "pre-intermediate",     // GSE 36-46: Pre-Intermediate (A2-B1-)
                "46-58" => "intermediate",         // GSE 46-58: Intermediate (B1)
                "57-67" => "upper-intermediate",   // GSE 57-67: Upper Intermediate (B2)
                "66-78" => "advanced"              // GSE 66-78: Advanced (C1)
            ]
        ]);
    }
}
