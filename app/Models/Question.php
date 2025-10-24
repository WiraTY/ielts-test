<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'type',
        'question_text',
        'options',
        'answer_key',
        'score'
    ];

    protected $casts = [
        'options' => 'array',
        'answer_key' => 'array',
        'score' => 'integer'
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }
}
