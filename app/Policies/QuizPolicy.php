<?php

namespace App\Policies;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class QuizPolicy
{
    /**
     * Determine whether the user can view any models.
     * Students can only view quizzes in trial courses.
     * Admins can view all quizzes.
     */
    public function viewAny(User $user): bool
    {
        // Both students and admins can view quizzes
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Students can only view quizzes in trial courses.
     * Admins can view all quizzes.
     */
    public function view(User $user, Quiz $quiz): bool
    {
        // Admins can view all quizzes
        if ($user->isAdmin()) {
            return true;
        }

        // Students can only view quizzes in trial courses
        if ($quiz->lesson) {
            return $quiz->lesson->course->is_trial;
        }

        // If quiz is directly associated with a course (not through lesson)
        if ($quiz->course) {
            return $quiz->course->is_trial;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     * Only admins can create quizzes.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Only admins can update quizzes.
     */
    public function update(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete quizzes.
     */
    public function delete(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore quizzes.
     */
    public function restore(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can permanently delete quizzes.
     */
    public function forceDelete(User $user, Quiz $quiz): bool
    {
        return $user->isAdmin();
    }
}
