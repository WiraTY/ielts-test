<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'email_verified_at',
        'has_taken_placement_test',
        'assigned_level',
        'current_level',
        'unlocked_levels',
        'toefl_target_score',
        'toefl_latest_reading_score',
        'toefl_latest_listening_score',
        'toefl_latest_speaking_score',
        'toefl_latest_writing_score',
        'toefl_test_date',
        'toefl_weak_areas',
        'toefl_study_notes',
        'has_taken_toefl_diagnostic'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_taken_placement_test' => 'boolean',
            'unlocked_levels' => 'array',
            'has_taken_toefl_diagnostic' => 'boolean',
            'toefl_target_score' => 'integer',
            'toefl_latest_reading_score' => 'integer',
            'toefl_latest_listening_score' => 'integer',
            'toefl_latest_speaking_score' => 'integer',
            'toefl_latest_writing_score' => 'integer',
            'toefl_test_date' => 'date',
            'toefl_weak_areas' => 'array'
        ];
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get user's placement test attempts
     */
    public function placementTestAttempts(): HasMany
    {
        return $this->hasMany(PlacementTestAttempt::class);
    }

    /**
     * Get user's course progress
     */
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * Check if user has access to a specific level
     */
    public function hasAccessToLevel($level): bool
    {
        $unlockedLevels = $this->unlocked_levels ?? [];
        return in_array($level, $unlockedLevels) || $level === $this->current_level;
    }

    /**
     * Check if user has completed placement test
     */
    public function hasCompletedPlacementTest(): bool
    {
        return $this->has_taken_placement_test;
    }

    /**
     * Get user's assigned level
     */
    public function getAssignedLevel()
    {
        return $this->assigned_level ?? 'starter';
    }

    /**
     * Get user's current accessible level
     */
    public function getCurrentLevel()
    {
        return $this->current_level ?? 'starter';
    }
    
    /**
     * Set default value for current_level if null
     */
    protected function currentLevel(): Attribute
    {
        return Attribute::make(
            set: fn ($value) => $value ?? 'starter',
        );
    }
    
    /**
     * Check if user has completed a specific course
     */
    public function hasCompletedCourse(Course $course): bool
    {
        // Check if user has completed all lessons in the course
        foreach ($course->lessons as $lesson) {
            $progress = $this->progress()
                ->where('lesson_id', $lesson->id)
                ->where('status', 'completed')
                ->exists();
                
            if (!$progress) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get unlocked level names as an array
     */
    public function getUnlockedLevelNames(): array
    {
        return $this->unlocked_levels ?? [];
    }

    // ========== TOEFL-Specific Methods ==========

    /**
     * Get user's TOEFL scores
     */
    public function toeflScores(): HasMany
    {
        return $this->hasMany(ToeflScore::class);
    }

    /**
     * Get user's TOEFL diagnostic attempts
     */
    public function toeflDiagnosticAttempts(): HasMany
    {
        return $this->hasMany(ToeflDiagnosticAttempt::class);
    }

    /**
     * Get user's TOEFL practice sessions
     */
    public function toeflPracticeSessions(): HasMany
    {
        return $this->hasMany(ToeflPracticeSession::class);
    }

    /**
     * Get user's latest TOEFL total score
     */
    public function getLatestToeflTotalScore(): int
    {
        return ($this->toefl_latest_reading_score ?? 0) +
               ($this->toefl_latest_listening_score ?? 0) +
               ($this->toefl_latest_speaking_score ?? 0) +
               ($this->toefl_latest_writing_score ?? 0);
    }

    /**
     * Get user's latest TOEFL section scores
     */
    public function getLatestToeflSectionScores(): array
    {
        return [
            'reading' => $this->toefl_latest_reading_score ?? 0,
            'listening' => $this->toefl_latest_listening_score ?? 0,
            'speaking' => $this->toefl_latest_speaking_score ?? 0,
            'writing' => $this->toefl_latest_writing_score ?? 0,
            'total' => $this->getLatestToeflTotalScore()
        ];
    }

    /**
     * Check if user has completed TOEFL diagnostic
     */
    public function hasCompletedToeflDiagnostic(): bool
    {
        return $this->has_taken_toefl_diagnostic;
    }

    /**
     * Get user's TOEFL weak areas
     */
    public function getToeflWeakAreas(): array
    {
        return $this->toefl_weak_areas ?? [];
    }

    /**
     * Check if user meets target TOEFL score
     */
    public function meetsToeflTarget(): bool
    {
        return $this->getLatestToeflTotalScore() >= ($this->toefl_target_score ?? 80);
    }

    /**
     * Get user's TOEFL progress percentage
     */
    public function getToeflProgressPercentage(): float
    {
        if (!$this->toefl_target_score) {
            return 0;
        }

        return min(100, ($this->getLatestToeflTotalScore() / $this->toefl_target_score) * 100);
    }

    /**
     * Get user's TOEFL performance level
     */
    public function getToeflPerformanceLevel(): string
    {
        $totalScore = $this->getLatestToeflTotalScore();

        if ($totalScore >= 110) {
            return 'Expert';
        } elseif ($totalScore >= 95) {
            return 'Very Good';
        } elseif ($totalScore >= 80) {
            return 'Good';
        } elseif ($totalScore >= 65) {
            return 'Fair';
        } elseif ($totalScore >= 50) {
            return 'Limited';
        } else {
            return 'Very Limited';
        }
    }

    /**
     * Update user's latest TOEFL scores
     */
    public function updateToeflScores(array $scores): void
    {
        $this->toefl_latest_reading_score = $scores['reading'] ?? $this->toefl_latest_reading_score;
        $this->toefl_latest_listening_score = $scores['listening'] ?? $this->toefl_latest_listening_score;
        $this->toefl_latest_speaking_score = $scores['speaking'] ?? $this->toefl_latest_speaking_score;
        $this->toefl_latest_writing_score = $scores['writing'] ?? $this->toefl_latest_writing_score;
        $this->toefl_test_date = now();

        // Auto-detect weak areas (sections with scores below 20)
        $weakAreas = [];
        foreach (['reading', 'listening', 'speaking', 'writing'] as $section) {
            if (($scores[$section] ?? 0) < 20) {
                $weakAreas[] = $section;
            }
        }
        $this->toefl_weak_areas = $weakAreas;

        $this->save();
    }

    /**
     * Get TOEFL section performance summary
     */
    public function getToeflPerformanceSummary(): array
    {
        $scores = $this->getLatestToeflSectionScores();
        $summary = [];

        foreach ($scores as $section => $score) {
            if ($section === 'total') continue;

            $percentage = ($score / 30) * 100; // Convert to percentage of max score
            $summary[$section] = [
                'score' => $score,
                'percentage' => round($percentage, 2),
                'performance' => $this->getSectionPerformanceLabel($percentage),
                'is_weak_area' => in_array($section, $this->getToeflWeakAreas())
            ];
        }

        return $summary;
    }

    /**
     * Get performance label based on percentage
     */
    private function getSectionPerformanceLabel(float $percentage): string
    {
        if ($percentage >= 90) {
            return 'Excellent';
        } elseif ($percentage >= 75) {
            return 'Good';
        } elseif ($percentage >= 60) {
            return 'Satisfactory';
        } elseif ($percentage >= 40) {
            return 'Needs Improvement';
        } else {
            return 'Weak';
        }
    }
}
