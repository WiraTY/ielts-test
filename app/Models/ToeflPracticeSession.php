<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToeflPracticeSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'lesson_id',
        'section',
        'status',
        'started_at',
        'completed_at',
        'time_spent_seconds',
        'score',
        'total_possible',
        'accuracy_percentage',
        'feedback',
        'session_data'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'time_spent_seconds' => 'integer',
        'score' => 'integer',
        'total_possible' => 'integer',
        'accuracy_percentage' => 'decimal:2',
        'session_data' => 'array'
    ];

    const SECTIONS = ['reading', 'listening', 'speaking', 'writing'];
    const STATUSES = ['started', 'in_progress', 'completed', 'timeout'];

    /**
     * Get the user that owns the practice session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the course that owns the practice session.
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the lesson that owns the practice session.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the duration in human readable format.
     */
    public function getFormattedDuration(): string
    {
        $minutes = floor($this->time_spent_seconds / 60);
        $seconds = $this->time_spent_seconds % 60;

        return $minutes > 0 ?
            sprintf('%d min %d sec', $minutes, $seconds) :
            sprintf('%d sec', $seconds);
    }

    /**
     * Check if the session is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if the session is currently in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === 'in_progress';
    }

    /**
     * Mark the session as completed.
     */
    public function markAsCompleted(int $score = null, string $feedback = null): void
    {
        $this->status = 'completed';
        $this->completed_at = now();

        if ($score !== null) {
            $this->score = $score;
            $this->calculateAccuracy();
        }

        if ($feedback !== null) {
            $this->feedback = $feedback;
        }

        $this->save();
    }

    /**
     * Calculate and update accuracy percentage.
     */
    public function calculateAccuracy(): void
    {
        if ($this->total_possible > 0) {
            $this->accuracy_percentage = round(($this->score / $this->total_possible) * 100, 2);
            $this->save();
        }
    }

    /**
     * Get performance rating based on accuracy.
     */
    public function getPerformanceRating(): string
    {
        if ($this->accuracy_percentage >= 90) {
            return 'Excellent';
        } elseif ($this->accuracy_percentage >= 80) {
            return 'Good';
        } elseif ($this->accuracy_percentage >= 70) {
            return 'Satisfactory';
        } elseif ($this->accuracy_percentage >= 60) {
            return 'Needs Improvement';
        } else {
            return 'Poor';
        }
    }

    /**
     * Scope to get sessions by section.
     */
    public function scopeBySection($query, string $section)
    {
        return $query->where('section', $section);
    }

    /**
     * Scope to get sessions by status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to get completed sessions.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope to get recent sessions.
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get session data value by key.
     */
    public function getSessionData(string $key, $default = null)
    {
        $data = $this->session_data ?? [];
        return $data[$key] ?? $default;
    }

    /**
     * Set session data value.
     */
    public function setSessionData(string $key, $value): void
    {
        $data = $this->session_data ?? [];
        $data[$key] = $value;
        $this->session_data = $data;
        $this->save();
    }
}