<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'slug',
        'title',
        'description',
        'thumbnail_path',
        'is_trial',
        'created_by',
        'published_at',
        'order',
        'level'
    ];

    protected $casts = [
        'is_trial' => 'boolean',
        'published_at' => 'datetime',
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

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }

    // Scope to get courses by level
    public function scopeByLevel($query, $level)
    {
        return $query->where('level', $level);
    }

    // Scope to get active courses
    public function scopeActive($query)
    {
        return $query->where('is_trial', true)
                    ->whereNotNull('published_at');
    }
    
    /**
     * Get progress for a specific user
     */
    public function progressForUser(User $user)
    {
        return $this->hasMany(Progress::class)
            ->where('user_id', $user->id);
    }
    
    /**
     * Get user progress for this course
     */
    public function getUserProgress($userId)
    {
        $totalLessons = $this->lessons()->count();
        
        if ($totalLessons === 0) {
            return [
                'total' => 0,
                'completed' => 0,
                'percentage' => 0,
                'is_completed' => false
            ];
        }
        
        $completedLessons = $this->lessons()
            ->whereHas('progress', function ($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->where('status', 'completed');
            })
            ->count();
            
        $percentage = ($completedLessons / $totalLessons) * 100;
        
        return [
            'total' => $totalLessons,
            'completed' => $completedLessons,
            'percentage' => round($percentage, 2),
            'is_completed' => $completedLessons === $totalLessons
        ];
    }
}
