<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuctionEndTest extends TestCase
{
    use RefreshDatabase;

    protected $umkmOwner;
    protected $investor1;
    protected $investor2;
    protected $business;
    protected $auction;

    protected function setUp(): void
    {
        parent::setUp();

        // Create users
        $this->umkmOwner = User::factory()->create(['role' => 'umkm_owner']);
        $this->investor1 = User::factory()->create(['role' => 'investor']);
        $this->investor2 = User::factory()->create(['role' => 'investor']);

        // Create business
        $this->business = Business::factory()->create(['user_id' => $this->umkmOwner->id]);

        // Create auction
        $this->auction = Auction::factory()->create([
            'business_id' => $this->business->id,
            'funding_goal' => 200000000, // 200 juta
            'equity_percentage' => 20.00, // 20% equity
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'status' => 'active'
        ]);
    }

    /** @test */
    public function umkm_owner_can_view_bids_with_investor_details()
    {
        // Place bids
        $bid1 = $this->auction->placeBid($this->investor1, 400000000, 'Interested in this business', 30.0); // 400 juta for 30% equity
        $bid2 = $this->auction->placeBid($this->investor2, 300000000, 'Great opportunity', 25.0); // 300 juta for 25% equity

        // UMKM owner can view bids with investor details
        $response = $this->actingAs($this->umkmOwner)->get(route('umkm.auctions.bids', $this->auction));

        $response->assertStatus(200);
        $response->assertSee($this->investor1->name);
        $response->assertSee($this->investor1->email);
        $response->assertSee('400.000.000');
        $response->assertSee('30%');
        $response->assertSee($this->investor2->name);
        $response->assertSee($this->investor2->email);
        $response->assertSee('300.000.000');
        $response->assertSee('25%');
    }

    /** @test */
    public function umkm_owner_can_end_auction_and_select_winner()
    {
        // Place bids
        $bid1 = $this->auction->placeBid($this->investor1, 400000000, 'Interested in this business', 30.0); // 400 juta for 30% equity
        $bid2 = $this->auction->placeBid($this->investor2, 300000000, 'Great opportunity', 25.0); // 300 juta for 25% equity

        // UMKM owner ends auction and selects investor2's bid as winner (300 juta for 25% equity)
        $response = $this->actingAs($this->umkmOwner)->post(route('umkm.auctions.end', $this->auction), [
            'bid_id' => $bid2->id
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        // Refresh models
        $this->auction->refresh();
        $bid1->refresh();
        $bid2->refresh();

        // Verify auction status is ended
        $this->assertEquals('ended', $this->auction->status);

        // Verify winning bid status is won
        $this->assertEquals('won', $bid2->status);

        // Verify losing bid status is lost
        $this->assertEquals('lost', $bid1->status);

        // Verify auction has correct winner
        $this->assertEquals($this->investor2->id, $this->auction->current_highest_bidder_id);
        $this->assertEquals(300000000, $this->auction->current_highest_bid);
    }

    /** @test */
    public function non_umkm_owner_cannot_end_auction()
    {
        // Place bid
        $bid = $this->auction->placeBid($this->investor1, 300000000, 'Great opportunity', 25.0);

        // Investor tries to end auction
        $response = $this->actingAs($this->investor1)->post(route('umkm.auctions.end', $this->auction), [
            'bid_id' => $bid->id
        ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function cannot_select_invalid_bid_as_winner()
    {
        // Create another auction
        $otherAuction = Auction::factory()->create([
            'business_id' => $this->business->id,
            'funding_goal' => 100000000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'status' => 'active'
        ]);

        // Place bid on other auction
        $otherBid = $otherAuction->placeBid($this->investor1, 150000000, 'Interested', 15.0);

        // Try to select bid from different auction as winner
        $response = $this->actingAs($this->umkmOwner)->post(route('umkm.auctions.end', $this->auction), [
            'bid_id' => $otherBid->id
        ]);

        $response->assertStatus(422);
        $response->assertJson(['success' => false]);
    }
}