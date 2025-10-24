<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToeflScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_type',
        'reading_score',
        'listening_score',
        'speaking_score',
        'writing_score',
        'total_score',
        'test_date',
        'notes'
    ];

    protected $casts = [
        'test_date' => 'date',
        'reading_score' => 'integer',
        'listening_score' => 'integer',
        'speaking_score' => 'integer',
        'writing_score' => 'integer',
        'total_score' => 'integer'
    ];

    /**
     * Get the user that owns the TOEFL score.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the section scores as an array.
     */
    public function getSectionScores(): array
    {
        return [
            'reading' => $this->reading_score,
            'listening' => $this->listening_score,
            'speaking' => $this->speaking_score,
            'writing' => $this->writing_score
        ];
    }

    /**
     * Get the highest section score.
     */
    public function getHighestSectionScore(): array
    {
        $scores = $this->getSectionScores();
        $maxScore = max($scores);
        $sections = array_keys($scores, $maxScore);

        return [
            'score' => $maxScore,
            'sections' => $sections
        ];
    }

    /**
     * Get the lowest section score.
     */
    public function getLowestSectionScore(): array
    {
        $scores = $this->getSectionScores();
        $minScore = min($scores);
        $sections = array_keys($scores, $minScore);

        return [
            'score' => $minScore,
            'sections' => $sections
        ];
    }

    /**
     * Determine if this score meets minimum requirements.
     */
    public function meetsMinimumRequirement(int $minimumScore = 80): bool
    {
        return $this->total_score >= $minimumScore;
    }

    /**
     * Get performance level based on TOEFL score ranges.
     */
    public function getPerformanceLevel(): string
    {
        if ($this->total_score >= 110) {
            return 'Expert';
        } elseif ($this->total_score >= 95) {
            return 'Very Good';
        } elseif ($this->total_score >= 80) {
            return 'Good';
        } elseif ($this->total_score >= 65) {
            return 'Fair';
        } elseif ($this->total_score >= 50) {
            return 'Limited';
        } else {
            return 'Very Limited';
        }
    }

    /**
     * Scope to get scores by test type.
     */
    public function scopeByTestType($query, string $testType)
    {
        return $query->where('test_type', $testType);
    }

    /**
     * Scope to get scores within a date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('test_date', [$startDate, $endDate]);
    }

    /**
     * Get the percentage breakdown for each section.
     */
    public function getSectionPercentages(): array
    {
        return [
            'reading' => round(($this->reading_score / 30) * 100, 2),
            'listening' => round(($this->listening_score / 30) * 100, 2),
            'speaking' => round(($this->speaking_score / 30) * 100, 2),
            'writing' => round(($this->writing_score / 30) * 100, 2)
        ];
    }
}