<?php

namespace App\Livewire;

use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class QuizResult extends Component
{
    public QuizAttempt $attempt;
    public $answers;
    public $questions;

    public function mount(QuizAttempt $attempt)
    {
        // Ensure user can only view their own results
        if ($attempt->user_id !== auth()->id()) {
            abort(403);
        }
        
        // Check if user is authorized to view this quiz
        Gate::authorize('view', $attempt->quiz);
        
        $this->attempt = $attempt;
        $this->answers = $attempt->answers;
        $this->questions = $attempt->quiz->questions;
    }

    public function render()
    {
        return view('livewire.quiz-result');
    }
}
