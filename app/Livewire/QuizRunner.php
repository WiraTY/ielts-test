<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAnswer;
use App\Models\Progress;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Illuminate\Support\Facades\Log;

class QuizRunner extends Component
{
    public Quiz $quiz;
    public QuizAttempt $attempt;
    public $questions;
    public $answers = [];
    public $current = 0;
    public $timeLeft; // seconds
    public $isSubmitted = false;
    public $quizEndTime;

    public function mount(Quiz $quiz)
    {
        // Check if user is authorized to view this quiz
        Gate::authorize('view', $quiz);
        
        $this->quiz = $quiz;
        $this->questions = $quiz->questions;
        
        // Initialize answers array with empty values
        foreach ($this->questions as $index => $question) {
            if ($question->type === 'multi') {
                $this->answers[$index] = ['options' => []];
            } else {
                $this->answers[$index] = [];
            }
        }
        
        // Create or retrieve attempt
        $this->attempt = QuizAttempt::firstOrCreate(
            ['quiz_id' => $quiz->id, 'user_id' => auth()->id(), 'status' => 'in_progress'],
            ['started_at' => now()]
        );
        
        // Set quiz end time
        $this->quizEndTime = $this->attempt->started_at->addMinutes($quiz->duration_minutes);
        Log::info('Quiz start time: ' . $this->attempt->started_at);
        Log::info('Quiz end time: ' . $this->quizEndTime);
        Log::info('Quiz duration minutes: ' . $quiz->duration_minutes);
        $this->calculateTimeLeft();
    }

    public function calculateTimeLeft()
    {
        $now = now();
        Log::info('Current time: ' . $now);
        Log::info('Quiz end time: ' . $this->quizEndTime);
        
        if ($now->greaterThan($this->quizEndTime)) {
            $this->timeLeft = 0;
            Log::info('Time is up, submitting quiz');
            if (!$this->isSubmitted) {
                $this->submit();
            }
        } else {
            $this->timeLeft = $now->diffInSeconds($this->quizEndTime);
            Log::info('Time left: ' . $this->timeLeft . ' seconds');
        }
    }

    public function updatedAnswers()
    {
        // This method is called whenever answers are updated
        Log::info('Answers updated: ' . json_encode($this->answers));
    }

    public function nextQuestion()
    {
        if ($this->current < $this->questions->count() - 1) {
            $this->current++;
        }
    }

    public function prevQuestion()
    {
        if ($this->current > 0) {
            $this->current--;
        }
    }

    public function submit()
    {
        // Prevent multiple submissions
        if ($this->isSubmitted) {
            return;
        }
        
        Log::info('Submitting quiz');
        Log::info('Answers: ' . json_encode($this->answers));
        
        // Calculate score
        $totalScore = 0;
        
        foreach ($this->questions as $index => $question) {
            // Save answer
            $answerData = [
                'answer' => $this->answers[$index] ?? null,
                'is_correct' => $this->isAnswerCorrect($question, $this->answers[$index] ?? null),
                'score_awarded' => $this->calculateScoreForQuestion($question, $this->answers[$index] ?? null)
            ];
            
            Log::info('Saving answer for question ' . $question->id . ': ' . json_encode($answerData));
            
            $answer = QuizAnswer::updateOrCreate(
                [
                    'attempt_id' => $this->attempt->id,
                    'question_id' => $question->id
                ],
                $answerData
            );
            
            $totalScore += $answer->score_awarded;
        }
        
        // Update attempt
        $this->attempt->update([
            'finished_at' => now(),
            'score' => $totalScore,
            'status' => 'completed'
        ]);
        
        // Automatically mark the lesson as completed when quiz is completed
        $lesson = $this->quiz->lesson;
        if ($lesson) {
            $progress = Progress::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'lesson_id' => $lesson->id
                ],
                [
                    'status' => 'completed',
                    'completed_at' => now()
                ]
            );
        }
        
        $this->isSubmitted = true;
        
        // Emit event for UI update
        $this->dispatch('quizCompleted', $this->attempt->id);
    }

    private function isAnswerCorrect($question, $answer)
    {
        Log::info('Checking answer correctness for question ' . $question->id);
        Log::info('Question type: ' . $question->type);
        Log::info('Answer: ' . json_encode($answer));
        Log::info('Answer key: ' . json_encode($question->answer_key));
        
        // Simple implementation for MCQ
        if ($question->type === 'mcq') {
            $correct = isset($answer['option']) && $answer['option'] == $question->answer_key['correct'];
            Log::info('MCQ correct: ' . ($correct ? 'true' : 'false'));
            return $correct;
        }
        
        // For multi-select
        if ($question->type === 'multi') {
            $correctAnswers = $question->answer_key['correct'] ?? [];
            $userAnswers = $answer['options'] ?? [];
            
            // Ensure both are arrays
            if (!is_array($correctAnswers)) {
                $correctAnswers = [];
            }
            if (!is_array($userAnswers)) {
                $userAnswers = [];
            }
            
            $correct = empty(array_diff($correctAnswers, $userAnswers)) && empty(array_diff($userAnswers, $correctAnswers));
            Log::info('Multi-select correct: ' . ($correct ? 'true' : 'false'));
            return $correct;
        }
        
        // For essay, we'll assume it's correct for now (would need manual review in real app)
        if ($question->type === 'essay') {
            Log::info('Essay correct: true');
            return true;
        }
        
        Log::info('Unknown question type, correct: false');
        return false;
    }

    private function calculateScoreForQuestion($question, $answer)
    {
        $correct = $this->isAnswerCorrect($question, $answer);
        $score = $correct ? $question->score : 0;
        Log::info('Calculated score for question ' . $question->id . ': ' . $score);
        return $score;
    }

    public function render()
    {
        $this->calculateTimeLeft();
        return view('livewire.quiz-runner');
    }
}
