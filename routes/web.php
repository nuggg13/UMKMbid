<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [AuctionController::class, 'index'])->name('home');
Route::get('/auctions', [AuctionController::class, 'index'])->name('auctions.index');
Route::get('/auctions/{auction}', [AuctionController::class, 'show'])->name('auctions.show');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/admin/login', [AuthController::class, 'showAdminLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'adminLogin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('web');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get')->middleware('web'); // Fallback for emergencies

// Protected routes
Route::middleware('auth')->group(function () {
    // Shared profile routes for all authenticated users
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/photo', [ProfileController::class, 'deleteProfilePhoto'])->name('profile.photo.delete');
    // UMKM Owner routes
    Route::middleware('role:umkm_owner')->prefix('umkm')->name('umkm.')->group(function () {
        Route::get('/dashboard', function () {
            return view('umkm.dashboard');
        })->name('dashboard');
        
        Route::resource('businesses', BusinessController::class);
        Route::get('/businesses/{business}/auctions/create', [AuctionController::class, 'create'])->name('auctions.create');
        Route::post('/businesses/{business}/auctions', [AuctionController::class, 'store'])->name('auctions.store');
        Route::get('/auctions/{auction}/edit', [AuctionController::class, 'edit'])->name('auctions.edit');
        Route::put('/auctions/{auction}', [AuctionController::class, 'update'])->name('auctions.update');
        Route::delete('/auctions/{auction}', [AuctionController::class, 'destroy'])->name('auctions.destroy');
        Route::get('/auctions/{auction}/bids', [AuctionController::class, 'showBids'])->name('auctions.bids');
        Route::post('/auctions/{auction}/end', [AuctionController::class, 'endAuction'])->name('auctions.end');
        Route::get('/bids/{bid}', [BidController::class, 'show'])->name('bids.show');
    });
    
    // Investor routes
    Route::middleware('role:investor')->prefix('investor')->name('investor.')->group(function () {
        Route::get('/dashboard', function () {
            return view('investor.dashboard');
        })->name('dashboard');
        
        Route::post('/auctions/{auction}/bid', [BidController::class, 'store'])->name('bids.store');
        Route::get('/my-bids', [BidController::class, 'myBids'])->name('bids.index');
        Route::get('/my-bids/{bid}', [BidController::class, 'show'])->name('bids.show');
    });
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');
        
        Route::get('/businesses', [BusinessController::class, 'adminIndex'])->name('businesses.index');
        Route::get('/businesses/{business}', [BusinessController::class, 'adminShow'])->name('businesses.show');
        Route::put('/businesses/{business}/approve', [BusinessController::class, 'approve'])->name('businesses.approve');
        Route::put('/businesses/{business}/reject', [BusinessController::class, 'reject'])->name('businesses.reject');
        
        Route::get('/auctions', [AuctionController::class, 'adminIndex'])->name('auctions.index');
        Route::put('/auctions/{auction}/approve', [AuctionController::class, 'approve'])->name('auctions.approve');
        Route::put('/auctions/{auction}/reject', [AuctionController::class, 'reject'])->name('auctions.reject');
    });
});

// API routes for AJAX calls
Route::prefix('api')->group(function () {
    // Public auction data (viewable by everyone)
    Route::get('/auctions/{auction}', [AuctionController::class, 'apiShow']);
    Route::get('/auctions', [AuctionController::class, 'apiIndex']);
    Route::get('/auctions/{auction}/bids', [BidController::class, 'getBids']);
    
    // Authenticated routes
    Route::middleware('auth')->group(function () {
        Route::post('/auctions/{auction}/bid', [BidController::class, 'store']);
        Route::get('/my-businesses', [BusinessController::class, 'apiIndex']);
        Route::get('/my-recent-bids', [BidController::class, 'apiRecentBids']);
        Route::get('/dashboard/stats', function () {
            // Return dashboard statistics
            return response()->json([
                'total_auctions' => \App\Models\Auction::count(),
                'active_auctions' => \App\Models\Auction::where('status', 'active')->count(),
                'total_bids' => \App\Models\Bid::count(),
                'my_bids' => \App\Models\Bid::where('user_id', auth()->id())->count(),
                'my_active_bids' => \App\Models\Bid::where('user_id', auth()->id())
                    ->whereHas('auction', function ($query) {
                        $query->where('status', 'active')
                            ->where('end_date', '>', now());
                    })->count(),
                'total_businesses' => \App\Models\Business::count(),
                'total_funding' => \App\Models\Auction::where('status', 'ended')->sum('current_highest_bid'),
                'total_invested' => \App\Models\Bid::where('user_id', auth()->id())
                    ->whereHas('auction', function ($query) {
                        $query->where('status', 'ended');
                    })->sum('amount'),
                'total_investors' => \App\Models\User::where('role', 'investor')->count(),
            ]);
        });
    });
});

// Debug route to check auction data
Route::get('/debug-auction/{id}', function ($id) {
    $auction = \App\Models\Auction::with(['business.user', 'bids.user', 'currentHighestBidder'])->findOrFail($id);
    
    $winningBid = $auction->bids->where('status', 'won')->first();
    
    return response()->json([
        'auction' => $auction,
        'status' => $auction->status,
        'current_highest_bidder_id' => $auction->current_highest_bidder_id,
        'current_highest_bidder' => $auction->current_highest_bidder,
        'winning_bid' => $winningBid,
        'has_winner' => $auction->status === 'ended' && $winningBid
    ]);
});
