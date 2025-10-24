<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LessonAudio extends Model
{
    protected $table = 'lesson_audio'; // Explicitly set table name
    
    protected $fillable = [
        'lesson_id',
        'audio_path',
        'description',
        'is_enabled'
    ];

    protected $casts = [
        'is_enabled' => 'boolean'
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
