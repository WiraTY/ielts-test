<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PlacementTestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'placement_test_id',
        'question_text',
        'options',
        'correct_answer',
        'score',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
        'score' => 'integer',
        'order' => 'integer'
    ];

    public function placementTest(): BelongsTo
    {
        return $this->belongsTo(PlacementTest::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PlacementTestAnswer::class, 'question_id');
    }
}
