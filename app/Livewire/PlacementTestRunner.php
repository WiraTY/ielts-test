<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PlacementTest;
use App\Models\PlacementTestAttempt;
use App\Models\PlacementTestAnswer;
use Illuminate\Support\Facades\Auth;

class PlacementTestRunner extends Component
{
    public PlacementTest $placementTest;
    public PlacementTestAttempt $attempt;
    public $questions;
    public $currentQuestionIndex = 0;
    public $selectedAnswers = [];
    public $timeLeft;
    public $isSubmitted = false;
    public $timerInterval = null;

    public function mount(PlacementTest $placementTest)
    {
        $this->placementTest = $placementTest;
        $this->questions = $placementTest->questions()->orderBy('order')->get();
        
        // Check if user already has an attempt
        $existingAttempt = PlacementTestAttempt::where('user_id', Auth::id())
            ->where('placement_test_id', $placementTest->id)
            ->where('status', 'in_progress')
            ->first();
            
        if ($existingAttempt) {
            $this->attempt = $existingAttempt;
            $this->loadExistingAnswers();
        } else {
            // Create a new attempt
            $this->attempt = PlacementTestAttempt::create([
                'user_id' => Auth::id(),
                'placement_test_id' => $placementTest->id,
                'started_at' => now(),
                'status' => 'in_progress'
            ]);
        }
        
        // Initialize time left
        $this->timeLeft = $placementTest->duration_minutes ? $placementTest->duration_minutes * 60 : null;
        
        // Initialize selected answers array
        foreach ($this->questions as $index => $question) {
            $this->selectedAnswers[$index] = null;
        }
    }
    
    public function loadExistingAnswers()
    {
        // Load existing answers if any
        $answers = PlacementTestAnswer::where('attempt_id', $this->attempt->id)->get();
        foreach ($answers as $answer) {
            $questionIndex = $this->questions->search(function ($item) use ($answer) {
                return $item->id === $answer->question_id;
            });
            
            if ($questionIndex !== false) {
                $this->selectedAnswers[$questionIndex] = $answer->selected_answer;
            }
        }
    }
    
    public function selectAnswer($questionIndex, $answer)
    {
        $this->selectedAnswers[$questionIndex] = $answer;
        
        // Save the answer immediately
        $question = $this->questions[$questionIndex];
        $existingAnswer = PlacementTestAnswer::where('attempt_id', $this->attempt->id)
            ->where('question_id', $question->id)
            ->first();
            
        if ($existingAnswer) {
            $existingAnswer->update(['selected_answer' => $answer]);
        } else {
            PlacementTestAnswer::create([
                'attempt_id' => $this->attempt->id,
                'question_id' => $question->id,
                'selected_answer' => $answer,
            ]);
        }
    }
    
    public function nextQuestion()
    {
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }
    
    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }
    
    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }
    
    public function submitTest()
    {
        // Calculate score
        $score = 0;
        $totalQuestions = count($this->questions);
        
        foreach ($this->questions as $index => $question) {
            $selectedAnswer = $this->selectedAnswers[$index];
            if ($selectedAnswer && $selectedAnswer === $question->correct_answer) {
                $score += $question->score;
            }
        }
        
        // Update attempt
        $this->attempt->update([
            'finished_at' => now(),
            'score' => $score,
            'status' => 'completed'
        ]);
        
        // Assign level based on score
        $this->assignLevel($score);
        
        $this->isSubmitted = true;
        
        // Emit event for navigation
        $this->dispatch('testCompleted', attemptId: $this->attempt->id);
    }
    
    public function assignLevel($score)
    {
        $totalPossibleScore = $this->questions->sum('score');
        $percentage = $totalPossibleScore > 0 ? ($score / $totalPossibleScore) * 100 : 0;
        
        $assignedLevel = 'starter'; // Default level
        
        // Find the appropriate level based on score ranges
        foreach ($this->placementTest->level_mapping as $range => $level) {
            // Parse range like "0-20"
            if (strpos($range, '-') !== false) {
                list($min, $max) = explode('-', $range);
                if ($percentage >= (int)$min && $percentage <= (int)$max) {
                    $assignedLevel = $level;
                    break;
                }
            }
        }
        
        // Update user's placement test status and assigned level
        $user = Auth::user();
        $user->update([
            'has_taken_placement_test' => true,
            'assigned_level' => $assignedLevel,
            'current_level' => $assignedLevel,
            'unlocked_levels' => array_unique(array_merge($user->unlocked_levels ?? [], [$assignedLevel]))
        ]);
    }
    
    public function updateTime()
    {
        if ($this->timeLeft !== null && $this->timeLeft > 0) {
            $this->timeLeft--;
            
            // If time is up, auto-submit the test
            if ($this->timeLeft <= 0) {
                $this->submitTest();
            }
        }
    }
    
    public function render()
    {
        return view('livewire.placement-test-runner');
    }
}
