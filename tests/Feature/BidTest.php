<?php

namespace Tests\Feature;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\Business;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BidTest extends TestCase
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
            'funding_goal' => 1000000,
            'start_date' => now()->subDay(),
            'end_date' => now()->addDay(),
            'status' => 'active'
        ]);
    }

    /** @test */
    public function investor_can_place_bid_on_auction()
    {
        $response = $this->actingAs($this->investor)->post(route('investor.bids.store', $this->auction), [
            'amount' => 1500000,
            'message' => 'Interested in this business',
            'equity_percentage' => 20.5
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('bids', [
            'auction_id' => $this->auction->id,
            'user_id' => $this->investor->id,
            'amount' => 1500000,
            'message' => 'Interested in this business',
            'equity_percentage' => 20.5,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function previous_bid_is_deleted_when_investor_places_new_bid_on_same_auction()
    {
        // Place first bid
        $firstBid = $this->auction->placeBid($this->investor, 1200000, 'First bid', 15.0);
        
        // Verify first bid exists
        $this->assertDatabaseHas('bids', [
            'id' => $firstBid->id,
            'auction_id' => $this->auction->id,
            'user_id' => $this->investor->id,
            'amount' => 1200000
        ]);

        // Place second bid
        $secondBid = $this->auction->placeBid($this->investor, 1500000, 'Second bid', 20.0);
        
        // Verify second bid exists
        $this->assertDatabaseHas('bids', [
            'auction_id' => $this->auction->id,
            'user_id' => $this->investor->id,
            'amount' => 1500000
        ]);

        // Verify first bid no longer exists
        $this->assertDatabaseMissing('bids', [
            'id' => $firstBid->id
        ]);
    }

    /** @test */
    public function previous_bid_is_deleted_when_investor_places_new_bid_via_http_request()
    {
        // Place first bid via HTTP
        $response1 = $this->actingAs($this->investor)->post(route('investor.bids.store', $this->auction), [
            'amount' => 1200000,
            'message' => 'First bid',
            'equity_percentage' => 15.0
        ]);

        $response1->assertStatus(200);
        
        // Get the first bid ID
        $firstBidId = Bid::where('auction_id', $this->auction->id)
                         ->where('user_id', $this->investor->id)
                         ->first()->id;

        // Place second bid via HTTP
        $response2 = $this->actingAs($this->investor)->post(route('investor.bids.store', $this->auction), [
            'amount' => 1500000,
            'message' => 'Second bid',
            'equity_percentage' => 20.0
        ]);

        $response2->assertStatus(200);

        // Verify first bid no longer exists
        $this->assertDatabaseMissing('bids', [
            'id' => $firstBidId
        ]);

        // Verify only one bid exists for this user on this auction
        $this->assertEquals(1, Bid::where('auction_id', $this->auction->id)
                                  ->where('user_id', $this->investor->id)
                                  ->count());
    }

    /** @test */
    public function bids_from_different_investors_are_not_affected()
    {
        // Create another investor
        $anotherInvestor = User::factory()->create(['role' => 'investor']);

        // Place bid from first investor
        $firstBid = $this->auction->placeBid($this->investor, 1200000, 'First investor bid', 15.0);

        // Place bid from second investor
        $secondBid = $this->auction->placeBid($anotherInvestor, 1300000, 'Second investor bid', 16.0);

        // Verify both bids exist
        $this->assertDatabaseHas('bids', [
            'id' => $firstBid->id,
            'auction_id' => $this->auction->id,
            'user_id' => $this->investor->id
        ]);

        $this->assertDatabaseHas('bids', [
            'id' => $secondBid->id,
            'auction_id' => $this->auction->id,
            'user_id' => $anotherInvestor->id
        ]);
    }
}