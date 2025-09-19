<?php

namespace App\Livewire;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Progress;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class LessonViewer extends Component
{
    public Course $course;
    public Lesson $lesson;
    public $progress;
    public $quizAttempt;

    public function mount(Course $course, Lesson $lesson)
    {
        // Check if user is authorized to view this lesson
        Gate::authorize('view', $lesson);
        
        $this->course = $course;
        // Load lesson with quiz relation
        $this->lesson = $lesson->load('quizzes');
        
        // Get or create progress for this lesson
        if (auth()->check()) {
            $this->progress = Progress::firstOrCreate(
                ['user_id' => auth()->id(), 'lesson_id' => $lesson->id],
                ['status' => 'in_progress']
            );
            
            // Check if user has completed the quiz for this lesson
            if ($this->lesson->quizzes && $this->lesson->quizzes->count() > 0) {
                $quiz = $this->lesson->quizzes->first();
                $this->quizAttempt = QuizAttempt::where('user_id', auth()->id())
                    ->where('quiz_id', $quiz->id)
                    ->where('status', 'completed')
                    ->first();
            }
        }
    }

    public function markAsCompleted()
    {
        if (auth()->check()) {
            $this->progress->update([
                'status' => 'completed',
                'completed_at' => now()
            ]);
            
            // Emit event for UI update
            $this->dispatch('lessonCompleted');
        }
    }

    public function render()
    {
        return view('livewire.lesson-viewer');
    }
}
