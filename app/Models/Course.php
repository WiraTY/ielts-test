<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Progress;

class Course extends Model
{
    protected $fillable = [
        'slug',
        'title',
        'description',
        'thumbnail_path',
        'is_trial',
        'order',
        'created_by',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_trial' => 'boolean',
        'order' => 'integer'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }
    
    public function getUserProgress($userId)
    {
        $totalLessons = $this->lessons->count();
        
        if ($totalLessons === 0) {
            return [
                'total' => 0,
                'completed' => 0,
                'percentage' => 0,
                'is_completed' => false
            ];
        }
        
        $completedLessons = Progress::where('user_id', $userId)
            ->whereHas('lesson', function($query) {
                $query->where('course_id', $this->id);
            })
            ->where('status', 'completed')
            ->count();
        
        $percentage = ($completedLessons / $totalLessons) * 100;
        
        return [
            'total' => $totalLessons,
            'completed' => $completedLessons,
            'percentage' => round($percentage),
            'is_completed' => ($totalLessons > 0 && $totalLessons == $completedLessons)
        ];
    }
}
