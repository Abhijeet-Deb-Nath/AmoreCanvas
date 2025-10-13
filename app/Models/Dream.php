<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Dream extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'connection_id',
        'heading',
        'title',
        'description',
        'place',
        'validated_by_partner',
        'status',
        'destiny_date',
        'validated_at',
        'scheduled_at',
        'cherished_at',
        'fulfilled_at',
    ];

    protected $casts = [
        'validated_by_partner' => 'boolean',
        'destiny_date' => 'datetime',
        'validated_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'cherished_at' => 'datetime',
        'fulfilled_at' => 'datetime',
    ];

    /**
     * Relationships
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function connection()
    {
        return $this->belongsTo(Connection::class);
    }

    public function negotiations()
    {
        return $this->hasMany(DreamDestinyNegotiation::class)->orderBy('created_at', 'desc');
    }

    public function notifications()
    {
        return $this->hasMany(DreamNotification::class);
    }

    /**
     * Get the partner user
     */
    public function getPartner(User $currentUser)
    {
        $connection = $this->connection;
        return $connection->sender_id === $currentUser->id 
            ? $connection->receiver 
            : $connection->sender;
    }

    /**
     * Check if dream is validated by both partners
     */
    public function isShared()
    {
        return $this->validated_by_partner && $this->status !== 'solo';
    }

    /**
     * Check if dream has pending destiny date negotiation
     */
    public function hasPendingNegotiation()
    {
        return $this->negotiations()->where('status', 'pending')->exists();
    }

    /**
     * Get the latest pending negotiation
     */
    public function latestPendingNegotiation()
    {
        return $this->negotiations()->where('status', 'pending')->first();
    }

    /**
     * Mark dream as validated by partner
     */
    public function markAsValidated()
    {
        $this->update([
            'validated_by_partner' => true,
            'status' => 'shared',
            'validated_at' => now(),
        ]);
    }

    /**
     * Check if dream can be scheduled (both partners validated)
     */
    public function canBeScheduled()
    {
        return $this->status === 'shared' && !$this->hasPendingNegotiation();
    }

    /**
     * Mark dream as scheduled with destiny date
     */
    public function markAsScheduled(Carbon $destinyDate)
    {
        $this->update([
            'status' => 'scheduled',
            'destiny_date' => $destinyDate,
            'scheduled_at' => now(),
        ]);
    }

    /**
     * Move dream to cherished memories
     */
    public function moveToCherished()
    {
        $this->update([
            'status' => 'cherished',
            'cherished_at' => now(),
        ]);
    }

    /**
     * Mark dream as fulfilled
     */
    public function markAsFulfilled()
    {
        $this->update([
            'status' => 'fulfilled',
            'fulfilled_at' => now(),
        ]);
    }

    /**
     * Reschedule dream (move from cherished back to shared)
     */
    public function reschedule()
    {
        $this->update([
            'status' => 'shared',
            'destiny_date' => null,
            'scheduled_at' => null,
            'cherished_at' => null,
        ]);
    }

    /**
     * Scope for solo dreams (created by user, not validated by partner)
     */
    public function scopeSolo($query)
    {
        return $query->where('status', 'solo');
    }

    /**
     * Scope for shared dreams (validated by both partners)
     */
    public function scopeShared($query)
    {
        return $query->where('status', 'shared');
    }

    /**
     * Scope for scheduled dreams (bucket list)
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'scheduled');
    }

    /**
     * Scope for cherished dreams (date passed)
     */
    public function scopeCherished($query)
    {
        return $query->where('status', 'cherished');
    }

    /**
     * Scope for fulfilled dreams
     */
    public function scopeFulfilled($query)
    {
        return $query->where('status', 'fulfilled');
    }

    /**
     * Scope for dreams belonging to a connection
     */
    public function scopeForConnection($query, $connectionId)
    {
        return $query->where('connection_id', $connectionId);
    }
}