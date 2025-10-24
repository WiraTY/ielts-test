<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Video extends Model
{
    protected $fillable = [
        'lesson_id',
        'provider',
        'provider_id',
        'url',
        'thumbnail',
        'duration'
    ];

    protected $casts = [
        'duration' => 'integer'
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }
}
