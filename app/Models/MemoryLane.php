<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MemoryLane extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'love_letter_id',
        'letter_content',
        'heading',
        'title',
        'description',
        'story_date',
        'media_type',
        'media_path',
    ];

    protected $casts = [
        'story_date' => 'date',
    ];

    /**
     * Get the user who created this memory
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the love letter associated with this memory (if any)
     */
    public function loveLetter()
    {
        return $this->belongsTo(LoveLetter::class);
    }

    /**
     * Get all reviews for this memory
     */
    public function reviews()
    {
        return $this->hasMany(MemoryReview::class);
    }
}
