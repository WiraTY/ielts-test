<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'email_verified_at',
        'has_taken_placement_test',
        'assigned_level',
        'current_level',
        'unlocked_levels'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'has_taken_placement_test' => 'boolean',
            'unlocked_levels' => 'array'
        ];
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Get user's placement test attempts
     */
    public function placementTestAttempts(): HasMany
    {
        return $this->hasMany(PlacementTestAttempt::class);
    }

    /**
     * Get user's course progress
     */
    public function progress(): HasMany
    {
        return $this->hasMany(Progress::class);
    }

    /**
     * Check if user has access to a specific level
     */
    public function hasAccessToLevel($level): bool
    {
        $unlockedLevels = $this->unlocked_levels ?? [];
        return in_array($level, $unlockedLevels) || $level === $this->current_level;
    }

    /**
     * Check if user has completed placement test
     */
    public function hasCompletedPlacementTest(): bool
    {
        return $this->has_taken_placement_test;
    }

    /**
     * Get user's assigned level
     */
    public function getAssignedLevel()
    {
        return $this->assigned_level ?? 'starter';
    }

    /**
     * Get user's current accessible level
     */
    public function getCurrentLevel()
    {
        return $this->current_level ?? 'starter';
    }
    
    /**
     * Check if user has completed a specific course
     */
    public function hasCompletedCourse(Course $course): bool
    {
        // Check if user has completed all lessons in the course
        foreach ($course->lessons as $lesson) {
            $progress = $this->progress()
                ->where('lesson_id', $lesson->id)
                ->where('status', 'completed')
                ->exists();
                
            if (!$progress) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Get unlocked level names as an array
     */
    public function getUnlockedLevelNames(): array
    {
        return $this->unlocked_levels ?? [];
    }
}
