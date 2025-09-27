<?php

namespace App\Http\Controllers;

use App\Models\Auction;
use App\Models\Bid;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BidController extends Controller
{
    public function store(Request $request, Auction $auction)
    {
        // Check if user is an investor
        if (!Auth::user()->isInvestor()) {
            return response()->json([
                'success' => false,
                'message' => 'Only investors can place bids'
            ], 403);
        }

        // Check if auction is active
        if (!$auction->isActive()) {
            return response()->json([
                'success' => false,
                'message' => 'This auction is not active'
            ], 422);
        }

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:' . $auction->funding_goal, // Minimum is funding goal
            'equity_percentage' => 'nullable|numeric|min:0.01',
            'message' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $bid = $auction->placeBid(
                Auth::user(),
                $request->amount,
                $request->message,
                $request->equity_percentage
            );

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bid placed successfully',
                'bid' => $bid->load('user'),
                'auction' => $auction->fresh(['currentHighestBidder'])
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function getBids(Auction $auction)
    {
        $bids = $auction->bids()
                        ->with('user:id,name')
                        ->orderBy('amount', 'desc')
                        ->take(10)
                        ->get()
                        ->map(function ($bid) {
                            return [
                                'id' => $bid->id,
                                'amount' => $bid->amount,
                                'equity_percentage' => $bid->equity_percentage,
                                'message' => $bid->message,
                                'user_name' => $bid->user->name,
                                'user_id' => $bid->user_id,
                                'bid_time' => $bid->bid_time,
                                'created_at' => $bid->created_at
                            ];
                        });

        return response()->json($bids);
    }

    public function myBids()
    {
        $bids = Bid::with(['auction.business'])
                   ->where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->paginate(20);

        return view('investor.bids.index', compact('bids'));
    }

    public function show(Bid $bid)
    {
        // Check if user owns this bid, is admin, or is the UMKM owner of the auction
        if ($bid->user_id !== Auth::id() && 
            !Auth::user()->isAdmin() && 
            $bid->auction->business->user_id !== Auth::id()) {
            abort(403);
        }

        $bid->load(['auction.business', 'user']);
        
        return view('bids.show', compact('bid'));
    }

    public function apiRecentBids()
    {
        $bids = Bid::with(['auction'])
                   ->where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->take(10)
                   ->get()
                   ->map(function ($bid) {
                       return [
                           'id' => $bid->id,
                           'amount' => $bid->amount,
                           'equity_percentage' => $bid->equity_percentage,
                           'auction_title' => $bid->auction->title,
                           'created_at' => $bid->created_at
                       ];
                   });

        return response()->json($bids);
    }
}
