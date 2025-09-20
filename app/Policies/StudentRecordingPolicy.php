<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentRecording;
use Illuminate\Auth\Access\Response;

class StudentRecordingPolicy
{
    /**
     * Determine whether the user can view the student recording.
     */
    public function view(User $user, StudentRecording $studentRecording): bool
    {
        return $user->id === $studentRecording->user_id;
    }

    /**
     * Determine whether the user can delete the student recording.
     */
    public function delete(User $user, StudentRecording $studentRecording): bool
    {
        return $user->id === $studentRecording->user_id;
    }
}
