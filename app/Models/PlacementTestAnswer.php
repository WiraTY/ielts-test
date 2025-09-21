<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlacementTestAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_answer',
        'is_correct',
        'score_awarded'
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'score_awarded' => 'integer'
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(PlacementTestAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(PlacementTestQuestion::class, 'question_id');
    }
}
