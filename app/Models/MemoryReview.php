<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemoryReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'memory_lane_id',
        'reviewer_id',
        'review',
        'media_path',
    ];

    /**
     * Get the memory lane this review belongs to
     */
    public function memoryLane()
    {
        return $this->belongsTo(MemoryLane::class);
    }

    /**
     * Get the user who wrote this review
     */
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
