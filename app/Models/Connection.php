<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
        'bonded_at',
    ];

    protected $casts = [
        'bonded_at' => 'datetime',
    ];

    /**
     * Get the user who sent the heart invitation
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the user who received the heart invitation
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Check if the connection is an eternal bond (accepted)
     */
    public function isEternalBond()
    {
        return $this->status === 'accepted';
    }

    /**
     * Check if the connection is pending
     */
    public function isPending()
    {
        return $this->status === 'pending';
    }

    /**
     * Get the other user in the connection
     */
    public function getPartner($userId)
    {
        return $this->sender_id === $userId ? $this->receiver : $this->sender;
    }
}
