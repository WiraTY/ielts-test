<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlacementTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement_test_id',
        'user_id',
        'started_at',
        'finished_at',
        'score',
        'assigned_level',
        'status'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
        'score' => 'integer'
    ];

    public function placementTest(): BelongsTo
    {
        return $this->belongsTo(PlacementTest::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PlacementTestAnswer::class, 'attempt_id');
    }
}
