<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method \App\Models\Connection|null eternalBond()
 * @method bool hasEternalBond()
 * @method \App\Models\User|null partner()
 * @method \Illuminate\Database\Eloquent\Collection pendingInvitations()
 */
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'verification_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
        ];
    }

    /**
     * Check if user's email is verified
     */
    public function isEmailVerified(): bool
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Generate and save email verification token
     */
    public function generateVerificationToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $this->verification_token = $token;
        $this->save();
        return $token;
    }

    /**
     * Mark email as verified
     */
    public function markEmailAsVerified(): void
    {
        $this->email_verified_at = now();
        $this->verification_token = null;
        $this->save();
    }

    /**
     * Get all connections where user is the sender
     */
    public function sentInvitations()
    {
        return $this->hasMany(Connection::class, 'sender_id');
    }

    /**
     * Get all connections where user is the receiver
     */
    public function receivedInvitations()
    {
        return $this->hasMany(Connection::class, 'receiver_id');
    }

    /**
     * Get the user's eternal bond (accepted connection)
     */
    public function eternalBond()
    {
        return Connection::where(function ($query) {
            $query->where('sender_id', $this->id)
                  ->orWhere('receiver_id', $this->id);
        })->where('status', 'accepted')->first();
    }

    /**
     * Check if user has an eternal bond
     */
    public function hasEternalBond()
    {
        return $this->eternalBond() !== null;
    }

    /**
     * Get the user's partner (if they have an eternal bond)
     */
    public function partner()
    {
        $bond = $this->eternalBond();
        if (!$bond) return null;
        
        return $bond->sender_id === $this->id ? $bond->receiver : $bond->sender;
    }

    /**
     * Get pending invitations received
     */
    public function pendingInvitations()
    {
        return $this->receivedInvitations()->where('status', 'pending')->get();
    }

    /**
     * Get dreams created by this user
     */
    public function createdDreams()
    {
        return $this->hasMany(Dream::class, 'creator_id');
    }

    /**
     * Get all dreams for this user's connection (including partner's dreams)
     */
    public function allDreams()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        return Dream::where('connection_id', $bond->id)->get();
    }

    /**
     * Get dreams where this user is the wisher (solo dreams)
     */
    public function soloDreams()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        return Dream::where('connection_id', $bond->id)
                    ->where('creator_id', $this->id)
                    ->where('status', 'solo')
                    ->get();
    }

    /**
     * Get partner's solo dreams (dreams waiting for validation)
     */
    public function partnerSoloDreams()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        $partnerId = $bond->sender_id === $this->id ? $bond->receiver_id : $bond->sender_id;
        
        return Dream::where('connection_id', $bond->id)
                    ->where('creator_id', $partnerId)
                    ->where('status', 'solo')
                    ->get();
    }

    /**
     * Get shared dreams (validated by both)
     */
    public function sharedDreams()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        return Dream::where('connection_id', $bond->id)
                    ->whereIn('status', ['shared', 'planning'])
                    ->get();
    }

    /**
     * Get bucket list dreams (scheduled)
     */
    public function bucketListDreams()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        return Dream::where('connection_id', $bond->id)
                    ->where('status', 'scheduled')
                    ->orderBy('destiny_date', 'asc')
                    ->get();
    }

    /**
     * Get cherished memories (date passed)
     */
    public function cherishedMemories()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        return Dream::where('connection_id', $bond->id)
                    ->where('status', 'cherished')
                    ->orderBy('destiny_date', 'desc')
                    ->get();
    }

    /**
     * Get fulfilled dreams (lived in the dream)
     */
    public function fulfilledDreams()
    {
        $bond = $this->eternalBond();
        if (!$bond) return collect([]);
        
        return Dream::where('connection_id', $bond->id)
                    ->where('status', 'fulfilled')
                    ->orderBy('fulfilled_at', 'desc')
                    ->get();
    }
}

