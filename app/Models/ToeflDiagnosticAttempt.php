<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToeflDiagnosticAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'toefl_diagnostic_test_id',
        'user_id',
        'started_at',
        'finished_at',
        'reading_score',
        'listening_score',
        'speaking_score',
        'writing_score',
        'total_score',
        'status',
        'section_feedback',
        'overall_feedback',
        'recommendations'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'reading_score' => 'integer',
        'listening_score' => 'integer',
        'speaking_score' => 'integer',
        'writing_score' => 'integer',
        'total_score' => 'integer',
        'section_feedback' => 'array',
        'recommendations' => 'array'
    ];

    const STATUSES = ['in_progress', 'completed', 'timeout'];

    /**
     * Get the diagnostic test that owns the attempt.
     */
    public function diagnosticTest(): BelongsTo
    {
        return $this->belongsTo(ToeflDiagnosticTest::class);
    }

    /**
     * Get the user that owns the attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the answers for this attempt.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(ToeflDiagnosticAnswer::class);
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
     * Get the percentage score for each section.
     */
    public function getSectionPercentages(): array
    {
        $maxScore = 30; // TOEFL max score per section

        return [
            'reading' => round(($this->reading_score / $maxScore) * 100, 2),
            'listening' => round(($this->listening_score / $maxScore) * 100, 2),
            'speaking' => round(($this->speaking_score / $maxScore) * 100, 2),
            'writing' => round(($this->writing_score / $maxScore) * 100, 2)
        ];
    }

    /**
     * Get the performance level based on total score.
     */
    public function getPerformanceLevel(): array
    {
        return ToeflDiagnosticTest::calculateProficiencyLevel($this->total_score);
    }

    /**
     * Check if the attempt is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the attempt is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Calculate and set the total score.
     */
    public function calculateTotalScore(): void
    {
        $this->total_score = $this->reading_score + $this->listening_score +
                           $this->speaking_score + $this->writing_score;
        $this->save();
    }

    /**
     * Complete the attempt.
     */
    public function complete(array $sectionScores = null): void
    {
        $this->status = 'completed';
        $this->finished_at = now();

        if ($sectionScores) {
            $this->reading_score = $sectionScores['reading'] ?? $this->reading_score;
            $this->listening_score = $sectionScores['listening'] ?? $this->listening_score;
            $this->speaking_score = $sectionScores['speaking'] ?? $this->speaking_score;
            $this->writing_score = $sectionScores['writing'] ?? $this->writing_score;
        }

        $this->calculateTotalScore();

        // Generate feedback and recommendations
        $this->generateFeedback();
    }

    /**
     * Generate feedback and recommendations based on performance.
     */
    private function generateFeedback(): void
    {
        $sectionScores = $this->getSectionScores();
        $recommendations = ToeflDiagnosticTest::generateRecommendations($sectionScores);

        $this->recommendations = $recommendations;
        $this->overall_feedback = $this->generateOverallFeedback($sectionScores);
        $this->section_feedback = $this->generateSectionFeedback($sectionScores);

        $this->save();
    }

    /**
     * Generate overall feedback.
     */
    private function generateOverallFeedback(array $sectionScores): array
    {
        $totalScore = array_sum($sectionScores);
        $performance = $this->getPerformanceLevel();

        return [
            'total_score' => $totalScore,
            'performance_level' => $performance['level'],
            'performance_description' => $performance['description'],
            'strengths' => $this->identifyStrengths($sectionScores),
            'areas_for_improvement' => $this->identifyWeaknesses($sectionScores)
        ];
    }

    /**
     * Generate feedback for each section.
     */
    private function generateSectionFeedback(array $sectionScores): array
    {
        $feedback = [];

        foreach ($sectionScores as $section => $score) {
            $percentage = ($score / 30) * 100; // Convert to percentage of max score

            $feedback[$section] = [
                'score' => $score,
                'percentage' => round($percentage, 2),
                'performance' => $this->getSectionPerformanceLevel($percentage),
                'feedback' => $this->getSectionSpecificFeedback($section, $score)
            ];
        }

        return $feedback;
    }

    /**
     * Get performance level for a section based on percentage.
     */
    private function getSectionPerformanceLevel(float $percentage): string
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

    /**
     * Get section-specific feedback.
     */
    private function getSectionSpecificFeedback(string $section, int $score): string
    {
        if ($score >= 26) {
            return "Excellent performance in {$section}. You have mastered the key skills for this section.";
        } elseif ($score >= 22) {
            return "Good performance in {$section}. Continue practicing to reach excellence.";
        } elseif ($score >= 18) {
            return "Satisfactory performance in {$section}. Focus on targeted practice areas.";
        } elseif ($score >= 15) {
            return "Performance in {$section} needs improvement. Consider structured practice.";
        } else {
            return "Weak performance in {$section}. Focus on fundamental skills and seek additional support.";
        }
    }

    /**
     * Identify strongest sections.
     */
    private function identifyStrengths(array $sectionScores): array
    {
        $maxScore = max($sectionScores);
        $strengths = [];

        foreach ($sectionScores as $section => $score) {
            if ($score >= $maxScore - 2) { // Within 2 points of max
                $strengths[] = ucfirst($section);
            }
        }

        return $strengths;
    }

    /**
     * Identify weakest sections.
     */
    private function identifyWeaknesses(array $sectionScores): array
    {
        $minScore = min($sectionScores);
        $weaknesses = [];

        foreach ($sectionScores as $section => $score) {
            if ($score <= $minScore + 2) { // Within 2 points of min
                $weaknesses[] = ucfirst($section);
            }
        }

        return $weaknesses;
    }

    /**
     * Get the duration of the attempt.
     */
    public function getDuration(): ?int
    {
        if ($this->started_at && $this->finished_at) {
            return $this->finished_at->diffInSeconds($this->started_at);
        }

        return null;
    }

    /**
     * Get formatted duration.
     */
    public function getFormattedDuration(): string
    {
        $duration = $this->getDuration();

        if (!$duration) {
            return 'N/A';
        }

        $hours = floor($duration / 3600);
        $minutes = floor(($duration % 3600) / 60);
        $seconds = $duration % 60;

        if ($hours > 0) {
            return sprintf('%dh %dm %ds', $hours, $minutes, $seconds);
        } elseif ($minutes > 0) {
            return sprintf('%dm %ds', $minutes, $seconds);
        } else {
            return sprintf('%ds', $seconds);
        }
    }
}