<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoveLetter extends Model
{
    use HasFactory;

    protected $fillable = [
        'connection_id',
        'sender_id',
        'receiver_id',
        'title',
        'content',
        'scheduled_delivery_at',
        'delivered_at',
        'read_at',
        'is_in_memory_lane',
    ];

    protected $casts = [
        'scheduled_delivery_at' => 'datetime',
        'delivered_at' => 'datetime',
        'read_at' => 'datetime',
        'is_in_memory_lane' => 'boolean',
    ];

    /**
     * Get the connection this letter belongs to
     */
    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }

    /**
     * Get the sender of the letter
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver of the letter
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Check if the letter has been delivered
     */
    public function isDelivered()
    {
        return !is_null($this->delivered_at);
    }

    /**
     * Check if the letter has been read
     */
    public function isRead()
    {
        return !is_null($this->read_at);
    }

    /**
     * Mark the letter as delivered
     */
    public function markAsDelivered()
    {
        $this->update(['delivered_at' => now()]);
    }

    /**
     * Mark the letter as read
     */
    public function markAsRead()
    {
        if (is_null($this->read_at)) {
            $this->update(['read_at' => now()]);
        }
    }

    /**
     * Scope to get only delivered letters
     */
    public function scopeDelivered($query)
    {
        return $query->whereNotNull('delivered_at');
    }

    /**
     * Scope to get only unread letters
     */
    public function scopeUnread($query)
    {
        return $query->whereNotNull('delivered_at')->whereNull('read_at');
    }

    /**
     * Scope to get letters ready for delivery
     */
    public function scopeReadyForDelivery($query)
    {
        return $query->whereNull('delivered_at')
                     ->where('scheduled_delivery_at', '<=', now());
    }
}
