<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonSpeaking extends Model
{
    protected $table = 'lesson_speaking'; // Explicitly set table name
    
    protected $fillable = [
        'lesson_id',
        'duration',
        'description',
        'is_enabled'
    ];

    protected $casts = [
        'duration' => 'integer',
        'is_enabled' => 'boolean'
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
