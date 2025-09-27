<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bid extends Model
{
    use HasFactory;

    protected $fillable = [
        'auction_id',
        'user_id',
        'amount',
        'equity_percentage',
        'message',
        'status',
        'bid_time',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'equity_percentage' => 'decimal:2',
            'bid_time' => 'datetime',
        ];
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($bid) {
            if (!$bid->bid_time) {
                $bid->bid_time = now();
            }
        });
    }

    // Relationships
    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active';
    }

    public function isWinning()
    {
        return $this->status === 'winning';
    }

    public function hasWon()
    {
        return $this->status === 'won';
    }

    public function hasLost()
    {
        return $this->status === 'lost';
    }

    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByAuction($query, $auctionId)
    {
        return $query->where('auction_id', $auctionId);
    }

    public function scopeOrderByAmount($query, $direction = 'desc')
    {
        return $query->orderBy('amount', $direction);
    }
}
