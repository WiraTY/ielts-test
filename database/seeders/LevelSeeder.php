<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Level;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $levels = [
            [
                'name' => 'starter',
                'display_name' => 'Starter',
                'description' => 'Beginner learners (GSE 22-35, CEFR A1-A1+)',
                'order' => 1,
                'is_active' => true
            ],
            [
                'name' => 'elementary',
                'display_name' => 'Elementary',
                'description' => 'Basic learners (GSE 30-42, CEFR A1+-A2)',
                'order' => 2,
                'is_active' => true
            ],
            [
                'name' => 'pre-intermediate',
                'display_name' => 'Pre-Intermediate',
                'description' => 'Lower intermediate (GSE 36-46, CEFR A2-B1-)',
                'order' => 3,
                'is_active' => true
            ],
            [
                'name' => 'intermediate',
                'display_name' => 'Intermediate',
                'description' => 'Mid-intermediate (GSE 46-58, CEFR B1)',
                'order' => 4,
                'is_active' => true
            ],
            [
                'name' => 'upper-intermediate',
                'display_name' => 'Upper Intermediate',
                'description' => 'High intermediate (GSE 57-67, CEFR B2)',
                'order' => 5,
                'is_active' => true
            ],
            [
                'name' => 'advanced',
                'display_name' => 'Advanced',
                'description' => 'Advanced learners (GSE 66-78, CEFR C1)',
                'order' => 6,
                'is_active' => true
            ]
        ];

        foreach ($levels as $level) {
            Level::updateOrCreate(
                ['name' => $level['name']],
                $level
            );
        }
    }
}
