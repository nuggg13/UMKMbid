<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Auction extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_id',
        'title',
        'description',
        'funding_goal',
        'equity_percentage',
        'minimum_bid',
        'current_highest_bid',
        'current_highest_bidder_id',
        'start_date',
        'end_date',
        'status',
        'terms_conditions',
    ];

    protected function casts(): array
    {
        return [
            'funding_goal' => 'decimal:2',
            'equity_percentage' => 'decimal:2',
            'minimum_bid' => 'decimal:2',
            'current_highest_bid' => 'decimal:2',
            'start_date' => 'datetime',
            'end_date' => 'datetime',
            'terms_conditions' => 'array',
        ];
    }

    // Relationships
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function currentHighestBidder()
    {
        return $this->belongsTo(User::class, 'current_highest_bidder_id');
    }

    public function activeBids()
    {
        return $this->bids()->where('status', 'active')->orderBy('amount', 'desc');
    }

    // Helper methods
    public function isActive()
    {
        return $this->status === 'active' && 
               now()->between($this->start_date, $this->end_date);
    }

    public function isEnded()
    {
        return $this->status === 'ended' || now()->gt($this->end_date);
    }

    public function getTimeRemainingAttribute()
    {
        if ($this->isEnded()) {
            return null;
        }
        
        return $this->end_date->diffForHumans();
    }

    public function getFormattedFundingGoalAttribute()
    {
        return 'Rp ' . number_format($this->funding_goal, 0, ',', '.');
    }

    public function getFormattedCurrentHighestBidAttribute()
    {
        return 'Rp ' . number_format($this->current_highest_bid, 0, ',', '.');
    }

    public function placeBid($user, $amount, $message = null, $equityPercentage = null)
    {
        if (!$this->isActive()) {
            throw new \Exception('Auction is not active');
        }

        // Check if amount meets minimum (funding goal)
        if ($amount < $this->funding_goal) {
            throw new \Exception('Bid must meet or exceed the funding goal of Rp ' . number_format($this->funding_goal, 0, ',', '.'));
        }

        // Remove any previous bids by the same user on this auction
        $this->bids()->where('user_id', $user->id)->delete();

        // Mark previous highest bid as outbid only if this bid is higher than the current highest
        if ($this->current_highest_bid && $amount > $this->current_highest_bid) {
            if ($this->current_highest_bidder_id) {
                $this->bids()->where('user_id', $this->current_highest_bidder_id)
                             ->where('status', 'active')
                             ->update(['status' => 'outbid']);
            }
        }

        // Create new bid
        $bid = $this->bids()->create([
            'user_id' => $user->id,
            'amount' => $amount,
            'equity_percentage' => $equityPercentage,
            'message' => $message,
            'status' => 'active',
        ]);

        // Update auction with new highest bid only if this bid is higher
        if ($amount > $this->current_highest_bid) {
            $this->update([
                'current_highest_bid' => $amount,
                'current_highest_bidder_id' => $user->id,
            ]);
        }

        return $bid;
    }

    /**
     * End the auction and select a winner
     *
     * @param Bid $winningBid The bid to select as the winner
     * @return void
     */
    public function endAuction(Bid $winningBid)
    {
        // Update the auction status to ended
        $this->update([
            'status' => 'ended',
            'current_highest_bid' => $winningBid->amount,
            'current_highest_bidder_id' => $winningBid->user_id,
        ]);

        // Mark the winning bid as won
        $winningBid->update(['status' => 'won']);

        // Mark all other bids as lost
        $this->bids()
            ->where('id', '!=', $winningBid->id)
            ->update(['status' => 'lost']);
    }

    /**
     * Get all bids with investor details for the UMKM owner to review
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBidsWithInvestorDetails()
    {
        return $this->bids()
            ->with(['user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone');
            }])
            ->orderBy('amount', 'desc')
            ->get();
    }
}