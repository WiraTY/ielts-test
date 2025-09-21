<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\PlacementTestAttempt;
use App\Services\LevelAssignmentService;
use Illuminate\Support\Facades\Auth;

class PlacementTestResult extends Component
{
    public PlacementTestAttempt $attempt;
    public $questions;
    public $answers;
    public $scorePercentage;
    public $totalPossibleScore;

    public function mount(PlacementTestAttempt $attempt)
    {
        // Authorize that the user can view this attempt
        if ($attempt->user_id !== Auth::id()) {
            abort(403);
        }
        
        $this->attempt = $attempt;
        $this->questions = $attempt->placementTest->questions()->orderBy('order')->get();
        $this->answers = $attempt->answers->keyBy('question_id');
        
        // Calculate total possible score
        $this->totalPossibleScore = $this->questions->sum('score');
        
        // Calculate score percentage
        $this->scorePercentage = $this->totalPossibleScore > 0 
            ? ($attempt->score / $this->totalPossibleScore) * 100 
            : 0;
            
        // Jika ini adalah attempt pertama pengguna dan level belum ditetapkan, tetapkan level
        if (!$attempt->user->has_taken_placement_test && !$attempt->user->assigned_level) {
            $this->assignLevelToUser();
        }
    }
    
    /**
     * Menetapkan level kepada pengguna berdasarkan skor tes
     */
    private function assignLevelToUser(): void
    {
        $levelAssignmentService = new LevelAssignmentService();
        $levelAssignmentService->assignLevelToUser(
            $this->attempt->user, 
            $this->attempt->placementTest, 
            $this->attempt->score
        );
        
        // Update attempt dengan level yang ditetapkan
        $this->attempt->update([
            'assigned_level' => $this->attempt->user->assigned_level
        ]);
    }
    
    public function render()
    {
        return view('livewire.placement-test-result');
    }
}
