@extends('layouts.app')

@section('title', 'My Bids - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">My Bids</h1>
            <p class="text-gray-600 mt-2">Track your investment bids and their status</p>
        </div>
        <a href="{{ route('auctions.index') }}" class="btn-primary transition duration-200">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Place New Bid
        </a>
    </div>

    <!-- Filter and Search -->
    <div class="card mb-6 transition duration-200">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <select id="status-filter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="won">Won</option>
                    <option value="lost">Lost</option>
                </select>
                
                <input type="text" id="search-input" placeholder="Search by auction title..." 
                       class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                
                <button onclick="filterBids()" class="btn-primary transition duration-200">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Bids List -->
    @if($bids->count() > 0)
        <div class="space-y-6">
            @foreach($bids as $bid)
                <div class="card transition duration-200">
                    <div class="card-body">
                        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-800 mb-2">
                                    <a href="{{ route('auctions.show', $bid->auction) }}" class="hover:text-primary transition-colors duration-200">
                                        {{ $bid->auction->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-3">{{ $bid->auction->business->name }}</p>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>{{ $bid->auction->business->category }}</span>
                                    <span>•</span>
                                    <span>Bid placed {{ $bid->bid_time->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-2xl font-bold text-gray-800 mb-1">
                                    Rp {{ number_format($bid->amount, 0, ',', '.') }}
                                </div>
                                <span class="px-3 py-1 rounded-full text-sm font-medium
                                    @if($bid->status === 'active' && $bid->auction->status === 'active') bg-green-100 text-green-800
                                    @elseif($bid->status === 'won') bg-blue-100 text-blue-800
                                    @elseif($bid->status === 'lost' || $bid->auction->status === 'ended') bg-gray-100 text-gray-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    @if($bid->auction->status === 'active')
                                        @if($bid->auction->current_highest_bidder_id === $bid->user_id)
                                            Leading
                                        @else
                                            Outbid
                                        @endif
                                    @elseif($bid->auction->status === 'ended')
                                        @if($bid->auction->current_highest_bidder_id === $bid->user_id)
                                            Won
                                        @else
                                            Lost
                                        @endif
                                    @else
                                        {{ ucfirst($bid->auction->status) }}
                                    @endif
                                </span>
                            </div>
                        </div>

                        <!-- Bid Details -->
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 p-4 bg-gray-50 rounded-lg">
                            <div>
                                <span class="text-sm text-gray-500">Funding Goal</span>
                                <p class="font-semibold">Rp {{ number_format($bid->auction->funding_goal, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Equity Offered</span>
                                <p class="font-semibold">{{ $bid->auction->equity_percentage }}%</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Current Highest</span>
                                <p class="font-semibold">Rp {{ number_format($bid->auction->current_highest_bid ?: $bid->auction->funding_goal, 0, ',', '.') }}</p>
                            </div>
                            <div>
                                <span class="text-sm text-gray-500">Auction Status</span>
                                <p class="font-semibold">{{ ucfirst($bid->auction->status) }}</p>
                            </div>
                        </div>

                        <!-- Bid Message -->
                        @if($bid->message)
                            <div class="mb-4">
                                <span class="text-sm text-gray-500">Your Message:</span>
                                <p class="text-gray-700 italic">"{{ $bid->message }}"</p>
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="text-sm text-gray-500">
                                @if($bid->auction->status === 'active')
                                    Ends: {{ $bid->auction->end_date->format('M d, Y H:i') }}
                                    ({{ $bid->auction->end_date->diffForHumans() }})
                                @else
                                    Ended: {{ $bid->auction->end_date->format('M d, Y H:i') }}
                                @endif
                            </div>
                            <div class="flex flex-wrap gap-3">
                                <a href="{{ route('auctions.show', $bid->auction) }}" 
                                   class="text-primary hover:text-secondary font-medium transition duration-200">
                                    View Auction →
                                </a>
                                @if($bid->auction->status === 'active' && $bid->auction->current_highest_bidder_id !== $bid->user_id)
                                    <a href="{{ route('auctions.show', $bid->auction) }}#bidForm" 
                                       class="btn-primary transition duration-200">
                                        Place Higher Bid
                                    </a>
                                @endif
                            </div>
                        </div>

                        <!-- Success Message for Won Bids -->
                        @if($bid->auction->status === 'ended' && $bid->auction->current_highest_bidder_id === $bid->user_id)
                            <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-green-800 font-medium">Congratulations! You won this auction.</p>
                                        <p class="text-green-700 text-sm">The business owner will contact you soon to finalize the investment.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $bids->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="card transition duration-200">
            <div class="card-body text-center py-12">
                <svg class="w-24 h-24 text-gray-400 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <h3 class="text-2xl font-bold text-gray-600 mb-4">No Bids Yet</h3>
                <p class="text-gray-500 mb-8 max-w-md mx-auto">
                    You haven't placed any bids yet. Start exploring investment opportunities and place your first bid!
                </p>
                <a href="{{ route('auctions.index') }}" class="btn-primary transition duration-200">
                    Browse Auctions
                </a>
            </div>
        </div>
    @endif
</div>

<script>
function filterBids() {
    const status = document.getElementById('status-filter').value;
    const search = document.getElementById('search-input').value;
    
    const params = new URLSearchParams(window.location.search);
    
    if (status) {
        params.set('status', status);
    } else {
        params.delete('status');
    }
    
    if (search) {
        params.set('search', search);
    } else {
        params.delete('search');
    }
    
    window.location.search = params.toString();
}

// Auto-filter on Enter key press
document.getElementById('search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        filterBids();
    }
});
</script>
@endsection