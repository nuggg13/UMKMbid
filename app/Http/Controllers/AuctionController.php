<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Business;
use App\Models\Bid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class AuctionController extends Controller
{
    public function index(Request $request)
    {
        $query = Auction::with(['business.user', 'currentHighestBidder'])
                        ->where('status', 'active')
                        ->where('end_date', '>', now());

        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('business', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->filled('funding_range')) {
            switch ($request->funding_range) {
                case 'under_100m':
                    $query->where('funding_goal', '<', 100000000);
                    break;
                case '100m_500m':
                    $query->whereBetween('funding_goal', [100000000, 500000000]);
                    break;
                case '500m_1b':
                    $query->whereBetween('funding_goal', [500000000, 1000000000]);
                    break;
                case 'over_1b':
                    $query->where('funding_goal', '>', 1000000000);
                    break;
            }
        }

        $auctions = $query->orderBy('end_date', 'asc')->paginate(10);

        // Get statistics
        $stats = [
            'total_businesses' => Business::where('status', 'approved')->count(),
            'active_investors' => \App\Models\User::where('role', 'investor')->count(),
            'successful_deals' => Auction::where('status', 'ended')
                                        ->whereNotNull('current_highest_bidder_id')
                                        ->count(),
            'total_investment' => Auction::where('status', 'ended')
                                        ->sum('current_highest_bid')
        ];

        if ($request->expectsJson()) {
            return response()->json([
                'auctions' => $auctions,
                'stats' => $stats
            ]);
        }

        return view('welcome', compact('auctions', 'stats'));
    }

    public function show(Auction $auction)
    {
        // Load all necessary relationships including winning bid with user
        $auction->load(['business.user', 'bids.user', 'currentHighestBidder']);
        
        // Ensure we load the user relationship for the winning bid specifically
        $auction->load(['bids' => function ($query) {
            $query->where('status', 'won')->with('user:id,name');
        }]);
        
        // Get recent bids for display
        $recentBids = $auction->bids()
                             ->with('user:id,name')
                             ->orderBy('amount', 'desc')
                             ->take(10)
                             ->get()
                             ->map(function ($bid) {
                                 return [
                                     'id' => $bid->id,
                                     'amount' => $bid->amount,
                                     'message' => $bid->message,
                                     'user_name' => $bid->user->name,
                                     'user_id' => $bid->user_id,
                                     'bid_time' => $bid->bid_time,
                                     'created_at' => $bid->created_at
                                 ];
                             });
        
        return view('auctions.show', compact('auction', 'recentBids'));
    }

    public function apiShow(Auction $auction)
    {
        $auction->load(['business.user', 'bids.user', 'currentHighestBidder']);
        
        return response()->json($auction);
    }

    public function apiIndex(Request $request)
    {
        $query = Auction::with(['business.user', 'currentHighestBidder'])
                        ->where('status', 'active')
                        ->where('end_date', '>', now());

        // Apply filters
        if ($request->filled('category')) {
            $query->whereHas('business', function ($q) use ($request) {
                $q->where('category', $request->category);
            });
        }

        if ($request->filled('funding_range')) {
            switch ($request->funding_range) {
                case 'under_100m':
                    $query->where('funding_goal', '<', 100000000);
                    break;
                case '100m_500m':
                    $query->whereBetween('funding_goal', [100000000, 500000000]);
                    break;
                case '500m_1b':
                    $query->whereBetween('funding_goal', [500000000, 1000000000]);
                    break;
                case 'over_1b':
                    $query->where('funding_goal', '>', 1000000000);
                    break;
            }
        }

        // Apply sorting
        switch ($request->get('sort', 'end_date')) {
            case 'funding_goal':
                $query->orderBy('funding_goal', 'desc');
                break;
            case 'equity_percentage':
                $query->orderBy('equity_percentage', 'desc');
                break;
            default:
                $query->orderBy('end_date', 'asc');
                break;
        }

        $perPage = $request->get('limit', 10);
        $auctions = $query->paginate($perPage);

        return response()->json($auctions);
    }

    public function create(Business $business)
    {
        // Check if user owns the business
        if ($business->user_id !== Auth::id()) {
            abort(403);
        }

        return view('umkm.auctions.create', compact('business'));
    }

    public function store(Request $request, Business $business)
    {
        // Debug logging
        Log::info('Auction store method called', [
            'business_id' => $business->id,
            'user_id' => Auth::id(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all()
        ]);

        // Check if user owns the business
        if ($business->user_id !== Auth::id()) {
            Log::warning('Unauthorized auction creation attempt', [
                'business_id' => $business->id,
                'business_user_id' => $business->user_id,
                'current_user_id' => Auth::id()
            ]);
            abort(403);
        }

        // Check if business is approved
        if (!$business->isApproved()) {
            Log::info('Business not approved for auction creation', [
                'business_id' => $business->id,
                'business_status' => $business->status
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Bisnis harus disetujui admin terlebih dahulu sebelum dapat membuat lelang.'
            ], 422);
        }

        // Prevent duplicate submissions - check if this business has created an auction in the last 30 seconds
        $recentAuction = $business->auctions()
            ->where('created_at', '>', now()->subSeconds(30))
            ->first();
            
        if ($recentAuction) {
            Log::info('Duplicate auction creation attempt blocked', [
                'business_id' => $business->id,
                'recent_auction_id' => $recentAuction->id
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Lelang baru saja dibuat. Silakan tunggu sebelum membuat lelang baru.'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'funding_goal' => 'required|numeric|min:1000000|max:10000000000', // Min 1M, Max 10B
            'equity_percentage' => 'required|numeric|min:1|max:49',
            'duration_days' => 'required|integer|min:1|max:30',
            'terms_conditions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            Log::info('Auction validation failed', [
                'business_id' => $business->id,
                'errors' => $validator->errors()->all()
            ]);
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Ensure minimum_bid is set if not provided
            $minimumBid = $request->funding_goal;
            
            // Cast duration_days to integer to avoid Carbon errors
            $durationDays = (int) $request->duration_days;
            
            Log::info('Creating auction with data', [
                'business_id' => $business->id,
                'title' => $request->title,
                'funding_goal' => $request->funding_goal,
                'equity_percentage' => $request->equity_percentage,
                'duration_days' => $durationDays,
                'minimum_bid' => $minimumBid,
                'terms_conditions' => $request->terms_conditions
            ]);
            
            $auction = $business->auctions()->create([
                'title' => $request->title,
                'description' => $request->description,
                'funding_goal' => $request->funding_goal,
                'equity_percentage' => $request->equity_percentage,
                'minimum_bid' => $minimumBid,
                'start_date' => now(),
                'end_date' => now()->addDays($durationDays),
                'status' => 'active',
                'terms_conditions' => $request->terms_conditions ?? []
            ]);

            // Refresh the auction to ensure it has an ID
            $auction->refresh();
            
            Log::info('Auction created successfully', [
                'auction_id' => $auction->id,
                'business_id' => $business->id
            ]);

            // Create redirect URL manually to avoid potential intl issues
            $redirectUrl = route('auctions.show', $auction->id);

            return response()->json([
                'success' => true,
                'message' => 'Lelang berhasil dibuat!',
                'auction' => [
                    'id' => $auction->id,
                    'title' => $auction->title,
                    'status' => $auction->status
                ],
                'redirect' => $redirectUrl
            ]);
        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Auction creation failed', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'stack_trace' => $e->getTraceAsString(),
                'business_id' => $business->id,
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);
            
            // Return more specific error message for debugging
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat lelang: ' . $e->getMessage(),
                'debug_info' => [
                    'file' => basename($e->getFile()),
                    'line' => $e->getLine()
                ]
            ], 500);
        }
    }

    public function edit(Auction $auction)
    {
        // Check if user owns the auction's business
        if ($auction->business->user_id !== Auth::id()) {
            abort(403);
        }

        return view('umkm.auctions.edit', compact('auction'));
    }

    public function update(Request $request, Auction $auction)
    {
        // Check if user owns the auction's business
        if ($auction->business->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow editing if no bids have been placed
        if ($auction->bids()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot edit auction that already has bids'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'funding_goal' => 'required|numeric|min:1000000|max:10000000000',
            'equity_percentage' => 'required|numeric|min:1|max:49',
            'duration_days' => 'nullable|integer|min:1|max:30', // Make it nullable since it might not always be present
            'terms_conditions' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $validator->validated();
        
        // Only update duration if it's provided
        if (isset($updateData['duration_days'])) {
            // Update the end_date based on duration_days
            $updateData['end_date'] = $auction->start_date->copy()->addDays($updateData['duration_days']);
            unset($updateData['duration_days']); // Remove duration_days as it's not a field in the model
        }
        
        $updateData['terms_conditions'] = $request->terms_conditions ?? [];
        
        $auction->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Auction updated successfully',
            'auction' => $auction
        ]);
    }

    public function destroy(Auction $auction)
    {
        // Check if user owns the auction's business
        if ($auction->business->user_id !== Auth::id()) {
            abort(403);
        }

        // Only allow deletion if no bids have been placed
        if ($auction->bids()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete auction that already has bids'
            ], 422);
        }

        $auction->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Auction cancelled successfully'
        ]);
    }

    // Admin methods
    public function adminIndex()
    {
        $auctions = Auction::with(['business.user'])
                          ->orderBy('created_at', 'desc')
                          ->paginate(20);

        return view('admin.auctions.index', compact('auctions'));
    }

    public function approve(Auction $auction)
    {
        $auction->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => 'Auction approved successfully'
        ]);
    }

    public function reject(Auction $auction)
    {
        $auction->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Auction rejected'
        ]);
    }

    /**
     * Show the bids for an auction with investor details (for UMKM owner)
     */
    public function showBids(Auction $auction)
    {
        // Check if user owns the auction's business
        if ($auction->business->user_id !== Auth::id()) {
            abort(403);
        }

        // Load bids with investor details
        $bids = $auction->getBidsWithInvestorDetails();

        return view('umkm.auctions.bids', compact('auction', 'bids'));
    }

    /**
     * End the auction and select a winner
     */
    public function endAuction(Request $request, Auction $auction)
    {
        // Check if user owns the auction's business
        if ($auction->business->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate the request
        $validator = Validator::make($request->all(), [
            'bid_id' => 'required|exists:bids,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid bid selection'
            ], 422);
        }

        // Find the winning bid
        $winningBid = Bid::findOrFail($request->bid_id);

        // Check if the bid belongs to this auction
        if ($winningBid->auction_id !== $auction->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid bid selection'
            ], 422);
        }

        try {
            // End the auction and select the winner
            $auction->endAuction($winningBid);

            return response()->json([
                'success' => true,
                'message' => 'Auction ended successfully. Winner selected.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to end auction: ' . $e->getMessage()
            ], 500);
        }
    }
}