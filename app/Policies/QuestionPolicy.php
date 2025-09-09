<?php

namespace App\Policies;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuestionPolicy
{
    /**
     * Determine whether the user can view any models.
     * Students can only view questions in quizzes from trial courses.
     * Admins can view all questions.
     */
    public function viewAny(User $user): bool
    {
        // Both students and admins can view questions
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Students can only view questions in quizzes from trial courses.
     * Admins can view all questions.
     */
    public function view(User $user, Question $question): bool
    {
        // Admins can view all questions
        if ($user->isAdmin()) {
            return true;
        }

        // Students can only view questions in quizzes from trial courses
        if ($question->quiz) {
            if ($question->quiz->lesson) {
                return $question->quiz->lesson->course->is_trial;
            }
            
            // If quiz is directly associated with a course (not through lesson)
            if ($question->quiz->course) {
                return $question->quiz->course->is_trial;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Only admins can create questions.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Only admins can update questions.
     */
    public function update(User $user, Question $question): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete questions.
     */
    public function delete(User $user, Question $question): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore questions.
     */
    public function restore(User $user, Question $question): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can permanently delete questions.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        return $user->isAdmin();
    }
}
