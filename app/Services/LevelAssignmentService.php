<?php

namespace App\Services;

use App\Models\PlacementTest;
use App\Models\User;

class LevelAssignmentService
{
    /**
     * Menentukan level berdasarkan skor tes penempatan
     *
     * @param PlacementTest $placementTest
     * @param int $score
     * @return string|null
     */
    public function determineLevelFromScore(PlacementTest $placementTest, int $score): ?string
    {
        $levelMapping = $placementTest->level_mapping;
        
        if (!$levelMapping || !is_array($levelMapping)) {
            return null;
        }
        
        // Urutkan level mapping berdasarkan range nilai (dari tertinggi ke terendah)
        uksort($levelMapping, function($a, $b) {
            $aRange = explode('-', $a);
            $bRange = explode('-', $b);
            return (int)$bRange[0] - (int)$aRange[0];
        });
        
        // Temukan level yang sesuai dengan skor
        foreach ($levelMapping as $range => $level) {
            if ($this->isScoreInRange($score, $range)) {
                return $level;
            }
        }
        
        // Jika tidak ada range yang cocok, kembalikan level default
        return 'starter';
    }
    
    /**
     * Memeriksa apakah skor berada dalam range tertentu
     *
     * @param int $score
     * @param string $range
     * @return bool
     */
    private function isScoreInRange(int $score, string $range): bool
    {
        // Parsing range seperti "0-20" atau "81-100"
        $parts = explode('-', $range);
        
        if (count($parts) !== 2) {
            return false;
        }
        
        $min = (int) trim($parts[0]);
        $max = (int) trim($parts[1]);
        
        return $score >= $min && $score <= $max;
    }
    
    /**
     * Menetapkan level ke pengguna berdasarkan skor tes penempatan
     *
     * @param User $user
     * @param PlacementTest $placementTest
     * @param int $score
     * @return void
     */
    public function assignLevelToUser(User $user, PlacementTest $placementTest, int $score): void
    {
        $assignedLevel = $this->determineLevelFromScore($placementTest, $score);
        
        if ($assignedLevel) {
            // Update user dengan level yang ditetapkan
            $user->update([
                'assigned_level' => $assignedLevel,
                'current_level' => $assignedLevel,
                'unlocked_levels' => [$assignedLevel], // Mulai dengan level yang ditetapkan
                'has_taken_placement_test' => true
            ]);
        }
    }
    
    /**
     * Membuka level berikutnya untuk pengguna
     *
     * @param User $user
     * @param string $level
     * @return void
     */
    public function unlockLevelForUser(User $user, string $level): void
    {
        $unlockedLevels = $user->unlocked_levels ?? [];
        
        if (!in_array($level, $unlockedLevels)) {
            $unlockedLevels[] = $level;
            $user->update(['unlocked_levels' => $unlockedLevels]);
        }
    }
    
    /**
     * Mendapatkan level berikutnya berdasarkan level saat ini
     *
     * @param string $currentLevel
     * @return string|null
     */
    public function getNextLevel(string $currentLevel): ?string
    {
        // Definisikan urutan level
        $levelSequence = ['starter', 'beginner', 'elementary', 'intermediate', 'advanced'];
        
        $currentIndex = array_search($currentLevel, $levelSequence);
        
        if ($currentIndex !== false && $currentIndex < count($levelSequence) - 1) {
            return $levelSequence[$currentIndex + 1];
        }
        
        return null; // Sudah mencapai level tertinggi
    }
}