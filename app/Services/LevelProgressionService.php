<?php

namespace App\Services;

use App\Models\User;
use App\Models\Course;
use App\Models\Progress;
use Illuminate\Support\Facades\DB;

class LevelProgressionService
{
    protected LevelAssignmentService $levelAssignmentService;
    
    public function __construct(LevelAssignmentService $levelAssignmentService)
    {
        $this->levelAssignmentService = $levelAssignmentService;
    }
    
    /**
     * Memeriksa apakah pengguna telah menyelesaikan semua kursus di level saat ini
     *
     * @param User $user
     * @return bool
     */
    public function hasUserCompletedCurrentLevel(User $user): bool
    {
        $currentLevel = $user->getCurrentLevel();
        
        // Dapatkan semua kursus di level saat ini
        $coursesAtCurrentLevel = Course::byLevel($currentLevel)->active()->get();
        
        // Jika tidak ada kursus di level ini, anggap saja sudah selesai
        if ($coursesAtCurrentLevel->isEmpty()) {
            return true;
        }
        
        // Periksa apakah pengguna telah menyelesaikan semua pelajaran di semua kursus di level ini
        foreach ($coursesAtCurrentLevel as $course) {
            foreach ($course->lessons as $lesson) {
                $progress = Progress::where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->first();
                
                // Jika tidak ada kemajuan atau statusnya bukan 'completed', maka belum selesai
                if (!$progress || $progress->status !== 'completed') {
                    return false;
                }
            }
        }
        
        // Jika semua pelajaran di semua kursus telah selesai, maka level saat ini telah selesai
        return true;
    }
    
    /**
     * Memajukan pengguna ke level berikutnya jika memenuhi syarat
     *
     * @param User $user
     * @return bool Apakah pengguna berhasil dipromosikan ke level berikutnya
     */
    public function advanceUserToNextLevel(User $user): bool
    {
        // Periksa apakah pengguna telah menyelesaikan level saat ini
        if (!$this->hasUserCompletedCurrentLevel($user)) {
            return false;
        }
        
        // Dapatkan level berikutnya
        $nextLevel = $this->levelAssignmentService->getNextLevel($user->getCurrentLevel());
        
        // Jika tidak ada level berikutnya, tidak perlu memajukan
        if (!$nextLevel) {
            return false;
        }
        
        // Buka level berikutnya untuk pengguna
        $this->levelAssignmentService->unlockLevelForUser($user, $nextLevel);
        
        // Update level saat ini pengguna
        $user->update(['current_level' => $nextLevel]);
        
        return true;
    }
    
    /**
     * Mendapatkan persentase penyelesaian level saat ini untuk pengguna
     *
     * @param User $user
     * @return float
     */
    public function getCurrentLevelCompletionPercentage(User $user): float
    {
        $currentLevel = $user->getCurrentLevel();
        
        // Dapatkan semua kursus di level saat ini
        $coursesAtCurrentLevel = Course::byLevel($currentLevel)->active()->get();
        
        // Jika tidak ada kursus di level ini, kembalikan 100%
        if ($coursesAtCurrentLevel->isEmpty()) {
            return 100;
        }
        
        $totalLessons = 0;
        $completedLessons = 0;
        
        // Hitung total pelajaran dan pelajaran yang telah selesai
        foreach ($coursesAtCurrentLevel as $course) {
            foreach ($course->lessons as $lesson) {
                $totalLessons++;
                
                $progress = Progress::where('user_id', $user->id)
                    ->where('lesson_id', $lesson->id)
                    ->first();
                
                if ($progress && $progress->status === 'completed') {
                    $completedLessons++;
                }
            }
        }
        
        // Kembalikan persentase penyelesaian
        return $totalLessons > 0 ? ($completedLessons / $totalLessons) * 100 : 0;
    }
    
    /**
     * Mendapatkan statistik kemajuan level untuk pengguna
     *
     * @param User $user
     * @return array
     */
    public function getLevelProgressStats(User $user): array
    {
        return [
            'current_level' => $user->getCurrentLevel(),
            'unlocked_levels' => $user->unlocked_levels ?? [],
            'completion_percentage' => $this->getCurrentLevelCompletionPercentage($user),
            'can_advance' => $this->hasUserCompletedCurrentLevel($user),
            'next_level' => $this->levelAssignmentService->getNextLevel($user->getCurrentLevel())
        ];
    }
}