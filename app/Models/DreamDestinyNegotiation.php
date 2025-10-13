<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DreamDestinyNegotiation extends Model
{
    use HasFactory;

    protected $fillable = [
        'dream_id',
        'proposed_by',
        'proposed_date',
        'message',
        'status',
        'responded_by',
        'responded_at',
    ];

    protected $casts = [
        'proposed_date' => 'datetime',
        'responded_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function dream()
    {
        return $this->belongsTo(Dream::class);
    }

    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposed_by');
    }

    public function responder()
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    /**
     * Accept the proposed date
     */
    public function accept(User $user)
    {
        $this->update([
            'status' => 'accepted',
            'responded_by' => $user->id,
            'responded_at' => now(),
        ]);
    }

    /**
     * Reject the proposed date
     */
    public function reject(User $user)
    {
        $this->update([
            'status' => 'rejected',
            'responded_by' => $user->id,
            'responded_at' => now(),
        ]);
    }

    /**
     * Mark as edited (counter-proposal)
     */
    public function markAsEdited()
    {
        $this->update([
            'status' => 'edited',
        ]);
    }
}