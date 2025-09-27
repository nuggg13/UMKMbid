<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuctionWinnerDisplayTest extends TestCase
{
    use RefreshDatabase;

    protected $umkmOwner;
    protected $investor;
    protected $business;
    protected $auction;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->umkmOwner = User::factory()->create(['role' => 'umkm_owner']);
        $this->investor = User::factory()->create(['role' => 'investor']);

        // Create business
        $this->business = Business::factory()->create(['user_id' => $this->umkmOwner->id]);

        // Create auction
        $this->auction = Auction::factory()->create([
            'business_id' => $this->business->id,
            'funding_goal' => 200000000,
            'equity_percentage' => 20.00,
            'start_date' => now()->subDay(),
            'end_date' => now()->subHour(),
            'status' => 'ended',
            'current_highest_bid' => 300000000,
            'current_highest_bidder_id' => $this->investor->id
        ]);
    }

    /** @test */
    public function winner_card_is_displayed_for_ended_auction_with_winner()
    {
        // Create a bid for the winner
        $bid = Bid::factory()->create([
            'auction_id' => $this->auction->id,
            'user_id' => $this->investor->id,
            'amount' => 300000000,
            'equity_percentage' => 25.00,
            'status' => 'won'
        ]);

        // Any user can view the auction details
        $response = $this->get(route('auctions.show', $this->auction));

        $response->assertStatus(200);
        $response->assertSee('Pemenang Lelang');
        $response->assertSee('Rp 300.000.000');
        $response->assertSee($this->investor->name);
        $response->assertSee('25% equity');
    }

    /** @test */
    public function winner_card_is_not_displayed_for_active_auction()
    {
        // Change auction status to active
        $this->auction->update(['status' => 'active', 'end_date' => now()->addDay()]);

        // Create a bid
        $bid = Bid::factory()->create([
            'auction_id' => $this->auction->id,
            'user_id' => $this->investor->id,
            'amount' => 300000000,
            'equity_percentage' => 25.00,
            'status' => 'active'
        ]);

        // Any user can view the auction details
        $response = $this->get(route('auctions.show', $this->auction));

        $response->assertStatus(200);
        $response->assertDontSee('Pemenang Lelang');
        $response->assertSee('Tawaran Tertinggi');
    }

    /** @test */
    public function winner_card_is_not_displayed_for_ended_auction_without_winner()
    {
        // Create auction without a winner
        $auctionWithoutWinner = Auction::factory()->create([
            'business_id' => $this->business->id,
            'funding_goal' => 200000000,
            'equity_percentage' => 20.00,
            'start_date' => now()->subDay(),
            'end_date' => now()->subHour(),
            'status' => 'ended',
            'current_highest_bid' => 0,
            'current_highest_bidder_id' => null
        ]);

        // Any user can view the auction details
        $response = $this->get(route('auctions.show', $auctionWithoutWinner));

        $response->assertStatus(200);
        $response->assertDontSee('Pemenang Lelang');
        $response->assertSee('Tawaran Tertinggi');
        $response->assertSee('Belum ada tawaran');
    }
}