<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;

class ToeflCourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@example.com')->first();

        if (!$admin) {
            $this->command->error('Admin user not found. Please run UserSeeder first.');
            return;
        }

        // Reading Section Courses
        $readingCourses = [
            [
                'title' => 'TOEFL Reading Fundamentals',
                'slug' => 'toefl-reading-fundamentals',
                'description' => 'Master the basics of TOEFL Reading with comprehensive coverage of factual information questions, vocabulary questions, and reading comprehension strategies.',
                'section_description' => 'Build strong foundation in TOEFL reading skills with targeted practice for beginner to intermediate levels.',
                'toefl_section' => 'reading',
                'is_toefl_practice' => true,
                'difficulty_level' => 'easy',
                'target_score_min' => 0,
                'target_score_max' => 20,
                'order' => 1,
                'published_at' => now()
            ],
            [
                'title' => 'Advanced TOEFL Reading Strategies',
                'slug' => 'advanced-toefl-reading-strategies',
                'description' => 'Advanced techniques for complex reading passages including inference questions, rhetorical purpose, and prose summary questions.',
                'section_description' => 'Take your reading skills to the next level with advanced strategies for high-scoring students.',
                'toefl_section' => 'reading',
                'is_toefl_practice' => true,
                'difficulty_level' => 'advanced',
                'target_score_min' => 20,
                'target_score_max' => 30,
                'order' => 2,
                'published_at' => now()
            ],
            [
                'title' => 'TOEFL Reading Speed & Accuracy',
                'slug' => 'toefl-reading-speed-accuracy',
                'description' => 'Improve reading speed while maintaining accuracy with timed practice sessions and strategic reading techniques.',
                'section_description' => 'Balance speed and precision for optimal performance in the reading section.',
                'toefl_section' => 'reading',
                'is_toefl_practice' => true,
                'difficulty_level' => 'intermediate',
                'target_score_min' => 15,
                'target_score_max' => 25,
                'order' => 3,
                'published_at' => now()
            ]
        ];

        // Listening Section Courses
        $listeningCourses = [
            [
                'title' => 'TOEFL Listening Comprehension Basics',
                'slug' => 'toefl-listening-comprehension-basics',
                'description' => 'Develop fundamental listening skills with focus on main ideas, details, and understanding conversations.',
                'section_description' => 'Build strong foundation in TOEFL listening with basic comprehension strategies.',
                'toefl_section' => 'listening',
                'is_toefl_practice' => true,
                'difficulty_level' => 'easy',
                'target_score_min' => 0,
                'target_score_max' => 20,
                'order' => 4,
                'published_at' => now()
            ],
            [
                'title' => 'Academic Listening & Note-Taking',
                'slug' => 'academic-listening-note-taking',
                'description' => 'Master academic lectures and develop effective note-taking strategies for complex information retention.',
                'section_description' => 'Excel in academic listening with professional note-taking techniques.',
                'toefl_section' => 'listening',
                'is_toefl_practice' => true,
                'difficulty_level' => 'intermediate',
                'target_score_min' => 15,
                'target_score_max' => 25,
                'order' => 5,
                'published_at' => now()
            ],
            [
                'title' => 'Advanced TOEFL Listening Techniques',
                'slug' => 'advanced-toefl-listening-techniques',
                'description' => 'Advanced listening skills including understanding speaker attitudes, organization, and complex academic content.',
                'section_description' => 'Perfect your listening with advanced techniques for high scores.',
                'toefl_section' => 'listening',
                'is_toefl_practice' => true,
                'difficulty_level' => 'advanced',
                'target_score_min' => 20,
                'target_score_max' => 30,
                'order' => 6,
                'published_at' => now()
            ]
        ];

        // Speaking Section Courses
        $speakingCourses = [
            [
                'title' => 'TOEFL Speaking Confidence Builder',
                'slug' => 'toefl-speaking-confidence-builder',
                'description' => 'Build confidence in speaking with pronunciation practice, fluency development, and basic response structures.',
                'section_description' => 'Develop speaking confidence and fundamental communication skills.',
                'toefl_section' => 'speaking',
                'is_toefl_practice' => true,
                'difficulty_level' => 'easy',
                'target_score_min' => 0,
                'target_score_max' => 18,
                'order' => 7,
                'published_at' => now()
            ],
            [
                'title' => 'Integrated Speaking Tasks Mastery',
                'slug' => 'integrated-speaking-tasks-mastery',
                'description' => 'Master integrated speaking tasks that combine reading and listening skills with clear, organized responses.',
                'section_description' => 'Excel in integrated speaking with comprehensive skill development.',
                'toefl_section' => 'speaking',
                'is_toefl_practice' => true,
                'difficulty_level' => 'intermediate',
                'target_score_min' => 18,
                'target_score_max' => 24,
                'order' => 8,
                'published_at' => now()
            ],
            [
                'title' => 'Advanced TOEFL Speaking Excellence',
                'slug' => 'advanced-toefl-speaking-excellence',
                'description' => 'Achieve speaking excellence with advanced vocabulary, natural delivery, and sophisticated response structures.',
                'section_description' => 'Reach speaking excellence with advanced communication techniques.',
                'toefl_section' => 'speaking',
                'is_toefl_practice' => true,
                'difficulty_level' => 'advanced',
                'target_score_min' => 24,
                'target_score_max' => 30,
                'order' => 9,
                'published_at' => now()
            ]
        ];

        // Writing Section Courses
        $writingCourses = [
            [
                'title' => 'TOEFL Writing Foundation',
                'slug' => 'toefl-writing-foundation',
                'description' => 'Build strong writing foundations with essay structure, paragraph development, and basic academic writing.',
                'section_description' => 'Master academic writing fundamentals and essay organization.',
                'toefl_section' => 'writing',
                'is_toefl_practice' => true,
                'difficulty_level' => 'easy',
                'target_score_min' => 0,
                'target_score_max' => 18,
                'order' => 10,
                'published_at' => now()
            ],
            [
                'title' => 'Integrated Writing Task Success',
                'slug' => 'integrated-writing-task-success',
                'description' => 'Master the integrated writing task with effective note-taking from reading and listening sources.',
                'section_description' => 'Excel in integrated writing with comprehensive source integration.',
                'toefl_section' => 'writing',
                'is_toefl_practice' => true,
                'difficulty_level' => 'intermediate',
                'target_score_min' => 18,
                'target_score_max' => 24,
                'order' => 11,
                'published_at' => now()
            ],
            [
                'title' => 'Independent Essay Excellence',
                'slug' => 'independent-essay-excellence',
                'description' => 'Develop sophisticated independent essay writing with advanced argumentation and academic vocabulary.',
                'section_description' => 'Achieve essay excellence with advanced writing techniques.',
                'toefl_section' => 'writing',
                'is_toefl_practice' => true,
                'difficulty_level' => 'advanced',
                'target_score_min' => 24,
                'target_score_max' => 30,
                'order' => 12,
                'published_at' => now()
            ]
        ];

        // Create all TOEFL courses
        $allCourses = array_merge($readingCourses, $listeningCourses, $speakingCourses, $writingCourses);

        foreach ($allCourses as $courseData) {
            $courseData['created_by'] = $admin->id;
            Course::create($courseData);
        }

        $this->command->info('Created 12 TOEFL practice courses across all four sections.');
    }
}