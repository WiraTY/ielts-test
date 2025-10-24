<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'thumbnail_path',
        'is_trial',
        'created_by',
        'published_at',
        'order',
        'level',
        'toefl_section',
        'target_score_min',
        'target_score_max',
        'section_description',
        'is_toefl_practice',
        'difficulty_level'
    ];

    protected $casts = [
        'is_trial' => 'boolean',
        'is_toefl_practice' => 'boolean',
        'published_at' => 'datetime',
        'order' => 'integer',
        'target_score_min' => 'integer',
        'target_score_max' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    // Scope to get courses by level
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Scope to get active courses
    public function scopeActive($query)
    {
        return $query->where('is_trial', true)
                    ->whereNotNull('published_at');
    }

    // Scope to get TOEFL courses
    public function scopeToefl($query)
    {
        return $query->where('is_toefl_practice', true);
    }

    // Scope to get courses by TOEFL section
    public function scopeByToeflSection($query, $section)
    {
        return $query->where('toefl_section', $section);
    }

    // Scope to get courses by difficulty level
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty_level', $difficulty);
    }
    
    /**
     * Get progress for a specific user
     */
    public function progressForUser(User $user)
    {
        return $this->hasMany(Progress::class)
            ->where('user_id', $user->id);
    }
    
    /**
     * Get user progress for this course
     */
    public function getUserProgress($userId)
    {
        $totalLessons = $this->lessons()->count();
        
        if ($totalLessons === 0) {
            return [
                'total' => 0,
                'completed' => 0,
                'percentage' => 0,
                'is_completed' => true  // Course with no lessons is considered completed
            ];
        }
        
        $completedLessons = $this->lessons()
            ->whereHas('progress', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'completed');
            })
            ->count();
            
        $percentage = ($completedLessons / $totalLessons) * 100;
        
        return [
            'total' => $totalLessons,
            'completed' => $completedLessons,
            'percentage' => round($percentage, 2),
            'is_completed' => $completedLessons === $totalLessons
        ];
    }

    // ========== TOEFL-Specific Methods ==========

    /**
     * Check if this is a TOEFL course
     */
    public function isToeflCourse(): bool
    {
        return $this->is_toefl_practice;
    }

    /**
     * Get the TOEFL section display name
     */
    public function getToeflSectionDisplayName(): string
    {
        $sections = [
            'reading' => 'Reading Section',
            'listening' => 'Listening Section',
            'speaking' => 'Speaking Section',
            'writing' => 'Writing Section',
            'general' => 'General TOEFL'
        ];

        return $sections[$this->toefl_section] ?? 'General Course';
    }

    /**
     * Get the difficulty level display name
     */
    public function getDifficultyLevelDisplayName(): string
    {
        $levels = [
            'easy' => 'Beginner',
            'intermediate' => 'Intermediate',
            'advanced' => 'Advanced'
        ];

        return $levels[$this->difficulty_level] ?? 'Intermediate';
    }

    /**
     * Check if course is suitable for user's score level
     */
    public function isSuitableForUserScore(int $userScore): bool
    {
        if (!$this->is_toefl_practice) {
            return true; // Non-TOEFL courses are available to everyone
        }

        return $userScore >= $this->target_score_min && $userScore <= $this->target_score_max;
    }

    /**
     * Get recommended courses based on user's weak areas
     */
    public static function getRecommendedForWeakAreas(array $weakAreas, int $userScore): \Illuminate\Database\Eloquent\Collection
    {
        return self::where('is_toefl_practice', true)
            ->whereIn('toefl_section', $weakAreas)
            ->where(function ($query) use ($userScore) {
                $query->whereNull('target_score_min')
                      ->orWhere('target_score_min', '<=', $userScore);
            })
            ->where(function ($query) use ($userScore) {
                $query->whereNull('target_score_max')
                      ->orWhere('target_score_max', '>=', $userScore);
            })
            ->active()
            ->orderBy('order')
            ->get();
    }

    /**
     * Get courses for TOEFL section
     */
    public static function getByToeflSection(string $section, string $difficulty = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = self::where('toefl_section', $section)
                   ->where('is_toefl_practice', true)
                   ->active();

        if ($difficulty) {
            $query->where('difficulty_level', $difficulty);
        }

        return $query->orderBy('order')->get();
    }

    /**
     * Get course description with TOEFL context
     */
    public function getFullDescription(): string
    {
        $description = $this->description ?? '';

        if ($this->is_toefl_practice && $this->toefl_section) {
            $sectionInfo = $this->getToeflSectionDisplayName();
            $difficultyInfo = $this->getDifficultyLevelDisplayName();

            if ($this->target_score_min && $this->target_score_max) {
                $scoreInfo = "Target Score: {$this->target_score_min}-{$this->target_score_max}/30";
                return "{$description}\n\n**{$sectionInfo} - {$difficultyInfo}**\n{$scoreInfo}";
            }

            return "{$description}\n\n**{$sectionInfo} - {$difficultyInfo}**";
        }

        return $description;
    }

    /**
     * Get the URL slug with TOEFL context
     */
    public function getToeflUrl(): string
    {
        if ($this->is_toefl_practice) {
            return "/toefl/{$this->toefl_section}/courses/{$this->slug}";
        }

        return "/courses/{$this->slug}";
    }
}
