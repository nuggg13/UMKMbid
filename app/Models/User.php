<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
        'role',
        'phone',
        'address',
        'identity_number',
        'is_verified',
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
            'is_verified' => 'boolean',
        ];
    }

    // Relationships
    public function businesses()
    {
        return $this->hasMany(Business::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function winningBids()
    {
        return $this->hasMany(Bid::class)->where('status', 'won');
    }

    // Helper methods
    public function isUmkmOwner()
    {
        return $this->role === 'umkm_owner';
    }

    public function isInvestor()
    {
        return $this->role === 'investor';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
