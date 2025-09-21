<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlacementTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'is_active',
        'level_mapping'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'level_mapping' => 'array'
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(PlacementTestQuestion::class);
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(PlacementTestAttempt::class);
    }
}
