<?php

namespace App\Policies;

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LessonPolicy
{
    /**
     * Determine whether the user can view any models.
     * Students can only view lessons in trial courses.
     * Admins can view all lessons.
     */
    public function viewAny(User $user): bool
    {
        // Both students and admins can view lessons
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Students can only view lessons in trial courses.
     * Admins can view all lessons.
     * Guests can view lessons in trial courses.
     */
    public function view(?User $user, Lesson $lesson): bool
    {
        // Admins can view all lessons
        if ($user && $user->isAdmin()) {
            return true;
        }

        // Students and guests can only view lessons in trial courses
        return $lesson->course->is_trial;
    }

    /**
     * Determine whether the user can create models.
     * Only admins can create lessons.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Only admins can update lessons.
     */
    public function update(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete lessons.
     */
    public function delete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore lessons.
     */
    public function restore(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can permanently delete lessons.
     */
    public function forceDelete(User $user, Lesson $lesson): bool
    {
        return $user->isAdmin();
    }
}
