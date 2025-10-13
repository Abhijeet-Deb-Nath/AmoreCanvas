<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DreamNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'dream_id',
        'notification_type',
        'scheduled_for',
        'status',
        'sent_at',
        'error_message',
    ];

    protected $casts = [
        'scheduled_for' => 'datetime',
        'sent_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function dream()
    {
        return $this->belongsTo(Dream::class);
    }

    /**
     * Mark notification as queued
     */
    public function markAsQueued()
    {
        $this->update(['status' => 'queued']);
    }

    /**
     * Mark notification as sent
     */
    public function markAsSent()
    {
        $this->update([
            'status' => 'sent',
            'sent_at' => now(),
        ]);
    }

    /**
     * Mark notification as failed
     */
    public function markAsFailed($errorMessage = null)
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $errorMessage,
        ]);
    }

    /**
     * Scope for pending notifications
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for due notifications
     */
    public function scopeDue($query)
    {
        return $query->where('scheduled_for', '<=', now())
                     ->whereIn('status', ['pending', 'queued']);
    }
}