<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Quiz;
use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TrialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'role' => 'student',
        ]);
        
        // Create an admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'role' => 'admin',
        ]);
        
        // Assign admin role (we'll use a simple approach for now)
        // In a real application, you would use a proper role/permission package

        // Create a trial course
        $course = Course::create([
            'slug' => 'english-basics',
            'title' => 'English Basics',
            'description' => 'Learn the fundamentals of English language',
            'is_trial' => true,
            'created_by' => $admin->id, // Admin creates the course
            'published_at' => now(),
        ]);

        // Create lessons
        $lesson1 = Lesson::create([
            'course_id' => $course->id,
            'slug' => 'introduction-to-english',
            'title' => 'Introduction to English',
            'content' => '<p>Welcome to English Basics! In this lesson, you will learn the fundamental concepts of English language.</p>',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Sample video URL
            'order' => 1,
        ]);

        $lesson2 = Lesson::create([
            'course_id' => $course->id,
            'slug' => 'basic-grammar',
            'title' => 'Basic Grammar',
            'content' => '<p>In this lesson, we will cover basic grammar rules including sentence structure, parts of speech, and punctuation.</p>',
            'video_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', // Sample video URL
            'order' => 2,
        ]);

        // Create quizzes for lessons
        $quiz1 = Quiz::create([
            'lesson_id' => $lesson1->id,
            'title' => 'Introduction Quiz',
            'duration_minutes' => 10,
            'pass_score' => 80,
        ]);

        $quiz2 = Quiz::create([
            'lesson_id' => $lesson2->id,
            'title' => 'Grammar Quiz',
            'duration_minutes' => 15,
            'pass_score' => 70,
        ]);

        // Create questions for quiz 1
        Question::create([
            'quiz_id' => $quiz1->id,
            'type' => 'mcq',
            'question_text' => 'What is the main topic of this course?',
            'options' => [
                'Advanced English Literature',
                'English Basics',
                'Business English',
                'English for Academic Purposes'
            ],
            'answer_key' => ['correct' => 1],
            'score' => 1,
        ]);

        Question::create([
            'quiz_id' => $quiz1->id,
            'type' => 'multi',
            'question_text' => 'Which of the following are language skills you will learn? (Select all that apply)',
            'options' => [
                'Reading',
                'Writing',
                'Speaking',
                'Cooking'
            ],
            'answer_key' => ['correct' => [0, 1, 2]],
            'score' => 2,
        ]);

        // Create questions for quiz 2
        Question::create([
            'quiz_id' => $quiz2->id,
            'type' => 'mcq',
            'question_text' => 'What is a noun?',
            'options' => [
                'A word that describes an action',
                'A word that names a person, place, thing, or idea',
                'A word that describes a noun',
                'A word that connects words or phrases'
            ],
            'answer_key' => ['correct' => 1],
            'score' => 1,
        ]);

        Question::create([
            'quiz_id' => $quiz2->id,
            'type' => 'essay',
            'question_text' => 'Write a simple sentence using a noun and a verb.',
            'score' => 2,
        ]);
    }
}
