<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ToeflDiagnosticTest;
use App\Models\ToeflDiagnosticQuestion;

class ToeflDiagnosticTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a comprehensive TOEFL Diagnostic Test
        $diagnosticTest = ToeflDiagnosticTest::create([
            'title' => 'Comprehensive TOEFL Diagnostic Test',
            'description' => 'A comprehensive diagnostic test to assess your TOEFL readiness across all four sections. This test includes representative question types from each section to help identify your strengths and areas for improvement.',
            'duration_minutes' => 120,
            'is_active' => true,
            'section_weights' => [
                'reading' => 0.25,
                'listening' => 0.25,
                'speaking' => 0.25,
                'writing' => 0.25
            ],
            'score_ranges' => [
                'expert' => ['min' => 110, 'max' => 120, 'description' => 'Expert User'],
                'very_good' => ['min' => 95, 'max' => 109, 'description' => 'Very Good User'],
                'good' => ['min' => 80, 'max' => 94, 'description' => 'Good User'],
                'fair' => ['min' => 65, 'max' => 79, 'description' => 'Fair User'],
                'limited' => ['min' => 50, 'max' => 64, 'description' => 'Limited User'],
                'very_limited' => ['min' => 0, 'max' => 49, 'description' => 'Very Limited User']
            ],
            'recommendations' => 'Based on your diagnostic results, you will receive personalized study recommendations and course suggestions.'
        ]);

        // Reading Section Questions
        $readingQuestions = [
            [
                'question_text' => 'According to the passage, which of the following is the primary reason for the decline in bee populations?',
                'options' => [
                    'Climate change affecting flower blooming cycles',
                    'Widespread use of pesticides in agriculture',
                    'Destruction of natural habitats by urban development',
                    'All of the above factors contribute significantly'
                ],
                'correct_answer' => 'D',
                'explanation' => 'The passage discusses how multiple factors including pesticides, habitat loss, and climate change all contribute to bee population decline.',
                'order' => 1
            ],
            [
                'question_text' => 'What can be inferred about the author\'s opinion on alternative energy sources?',
                'options' => [
                    'They are too expensive to implement',
                    'They require more government funding',
                    'They present viable solutions to environmental problems',
                    'They are not yet technologically advanced enough'
                ],
                'correct_answer' => 'C',
                'explanation' => 'The author presents various alternative energy sources positively, highlighting their potential benefits.',
                'order' => 2
            ],
            [
                'question_text' => 'The word "mitigate" in paragraph 3 is closest in meaning to:',
                'options' => [
                    'Eliminate',
                    'Reduce the severity of',
                    'Accelerate',
                    'Transform'
                ],
                'correct_answer' => 'B',
                'explanation' => 'In this context, "mitigate" means to reduce the severity or negative effects of something.',
                'order' => 3
            ]
        ];

        // Listening Section Questions
        $listeningQuestions = [
            [
                'question_text' => 'What is the main purpose of the lecture?',
                'options' => [
                    'To criticize current teaching methods',
                    'To propose a new educational theory',
                    'To compare different learning styles',
                    'To discuss the importance of critical thinking'
                ],
                'correct_answer' => 'D',
                'explanation' => 'The professor emphasizes how critical thinking skills are essential for academic success.',
                'order' => 4
            ],
            [
                'question_text' => 'According to the professor, what is the main advantage of the new research method?',
                'options' => [
                    'It is less expensive than traditional methods',
                    'It provides more accurate results',
                    'It requires less time to complete',
                    'It can be used in various research fields'
                ],
                'correct_answer' => 'B',
                'explanation' => 'The professor specifically mentions the improved accuracy and reliability of the new method.',
                'order' => 5
            ]
        ];

        // Speaking Section Questions
        $speakingQuestions = [
            [
                'question_text' => 'Some people prefer to study alone, while others prefer to study in groups. Which do you prefer and why? Use specific reasons and examples to support your answer.',
                'rubric' => [
                    'content' => 'Clear position with relevant examples',
                    'organization' => 'Logical structure and flow',
                    'language' => 'Grammar, vocabulary, pronunciation',
                    'fluency' => 'Natural delivery and pacing'
                ],
                'order' => 6
            ],
            [
                'question_text' => 'Read the short passage about university library policies. Then listen to a student expressing concerns about these policies. Respond to the student\'s concerns, taking into account both the passage and the audio.',
                'rubric' => [
                    'content_integration' => 'Integration of reading and listening',
                    'task_completion' => 'Response addresses all aspects',
                    'language_use' => 'Appropriate academic language',
                    'delivery' => 'Clear and coherent speech'
                ],
                'order' => 7
            ]
        ];

        // Writing Section Questions
        $writingQuestions = [
            [
                'question_text' => 'Read the academic passage about renewable energy. Then listen to a lecture that challenges the views presented in the passage. Write a response summarizing the points made in the lecture and explaining how they cast doubt on the points made in the reading passage.',
                'rubric' => [
                    'content' => 'Accurate summary and comparison',
                    'organization' => 'Clear essay structure',
                    'language' => 'Grammar and vocabulary',
                    'task_completion' => 'Addresses all requirements'
                ],
                'order' => 8
            ],
            [
                'question_text' => 'Do you agree or disagree with the following statement? "Universities should require all students to take basic computer science courses, regardless of their major." Use specific reasons and examples to support your answer.',
                'rubric' => [
                    'thesis_development' => 'Clear position with logical support',
                    'organization' => 'Well-structured paragraphs',
                    'language_proficiency' => 'Complex sentences and vocabulary',
                    'argumentation' => 'Strong reasoning and evidence'
                ],
                'order' => 9
            ]
        ];

        // Create reading questions
        foreach ($readingQuestions as $questionData) {
            ToeflDiagnosticQuestion::create([
                'toefl_diagnostic_test_id' => $diagnosticTest->id,
                'section' => 'reading',
                'question_text' => $questionData['question_text'],
                'options' => $questionData['options'],
                'correct_answer' => $questionData['correct_answer'],
                'score' => 10,
                'order' => $questionData['order'],
                'explanation' => $questionData['explanation'] ?? null
            ]);
        }

        // Create listening questions
        foreach ($listeningQuestions as $questionData) {
            ToeflDiagnosticQuestion::create([
                'toefl_diagnostic_test_id' => $diagnosticTest->id,
                'section' => 'listening',
                'question_text' => $questionData['question_text'],
                'options' => $questionData['options'],
                'correct_answer' => $questionData['correct_answer'],
                'score' => 10,
                'order' => $questionData['order'],
                'explanation' => $questionData['explanation'] ?? null
            ]);
        }

        // Create speaking questions
        foreach ($speakingQuestions as $questionData) {
            ToeflDiagnosticQuestion::create([
                'toefl_diagnostic_test_id' => $diagnosticTest->id,
                'section' => 'speaking',
                'question_text' => $questionData['question_text'],
                'score' => 15,
                'order' => $questionData['order'],
                'rubric' => $questionData['rubric']
            ]);
        }

        // Create writing questions
        foreach ($writingQuestions as $questionData) {
            ToeflDiagnosticQuestion::create([
                'toefl_diagnostic_test_id' => $diagnosticTest->id,
                'section' => 'writing',
                'question_text' => $questionData['question_text'],
                'score' => 15,
                'order' => $questionData['order'],
                'rubric' => $questionData['rubric']
            ]);
        }

        $this->command->info('TOEFL Diagnostic Test created with sample questions for all sections.');
    }
}