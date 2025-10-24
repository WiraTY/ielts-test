<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the next level in the progression sequence
     */
    public function nextLevel()
    {
        return self::where('order', '>', $this->order)
            ->where('is_active', true)
            ->orderBy('order')
            ->first();
    }

    /**
     * Get the previous level in the progression sequence
     */
    public function previousLevel()
    {
        return self::where('order', '<', $this->order)
            ->where('is_active', true)
            ->orderBy('order', 'desc')
            ->first();
    }

    /**
     * Scope for active levels
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering levels
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get all active level names
     */
    public static function getActiveLevelNames()
    {
        return self::active()->pluck('name')->toArray();
    }
}
