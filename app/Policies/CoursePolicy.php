<?php

namespace App\Policies;

use App\Models\Course;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CoursePolicy
{
    /**
     * Determine whether the user can view any models.
     * Students can only view trial courses.
     * Admins can view all courses.
     */
    public function viewAny(User $user): bool
    {
        // Both students and admins can view courses
        return true;
    }

    /**
     * Determine whether the user can view the model.
     * Students can only view trial courses at their level.
     * Admins can view all courses.
     * Guests can view trial courses.
     */
    public function view(?User $user, Course $course): bool
    {
        // Admins can view all courses
        if ($user && $user->isAdmin()) {
            return true;
        }

        // Students and guests can only view trial courses
        if (!$course->is_trial) {
            return false;
        }

        // Guests can only view starter level courses
        if (!$user) {
            return $course->level === 'starter';
        }

        // For students who haven't taken placement test, only starter courses are accessible
        if (!$user->hasCompletedPlacementTest()) {
            return $course->level === 'starter';
        }

        // Check if course level is in user's unlocked levels or is their current level
        return $user->hasAccessToLevel($course->level);
    }

    /**
     * Determine whether the user can create models.
     * Only admins can create courses.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     * Only admins can update courses.
     */
    public function update(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     * Only admins can delete courses.
     */
    public function delete(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     * Only admins can restore courses.
     */
    public function restore(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can permanently delete the model.
     * Only admins can permanently delete courses.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->isAdmin();
    }
}
