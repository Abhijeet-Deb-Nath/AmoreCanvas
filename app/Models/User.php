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
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
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
}

