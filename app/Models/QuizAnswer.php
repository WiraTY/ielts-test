<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAnswer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer',
        'is_correct',
        'score_awarded'
    ];

    protected $casts = [
        'answer' => 'array',
        'is_correct' => 'boolean',
        'score_awarded' => 'decimal:2'
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class);
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
