@extends('layouts.app')

@section('title', 'Bid Details - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Bid Details</h1>
            <p class="text-gray-600 mt-2">
                @if(auth()->user()->isInvestor())
                    View detailed information about your bid
                @elseif(auth()->user()->isUmkmOwner())
                    View detailed information about a bid on your auction
                @else
                    View bid details
                @endif
            </p>
        </div>
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('auctions.show', $bid->auction) }}" class="btn-primary transition duration-200">
                View Auction
            </a>
            @if(auth()->user()->isInvestor())
                <a href="{{ route('investor.bids.index') }}" class="btn-secondary transition duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to My Bids
                </a>
            @elseif(auth()->user()->isUmkmOwner())
                <a href="{{ route('umkm.businesses.index') }}" class="btn-secondary transition duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to My Businesses
                </a>
            @else
                <a href="{{ route('admin.dashboard') }}" class="btn-secondary transition duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back to Dashboard
                </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Bid Information -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Bid Information</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bid Amount</label>
                            <p class="text-2xl font-bold text-primary">Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bid Status</label>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($bid->status === 'active' && $bid->auction->current_highest_bidder_id === $bid->user_id) bg-green-100 text-green-800
                                @elseif($bid->status === 'won') bg-blue-100 text-blue-800
                                @elseif($bid->status === 'outbid') bg-yellow-100 text-yellow-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                @if($bid->auction->current_highest_bidder_id === $bid->user_id && $bid->auction->status === 'active')
                                    Leading Bid
                                @elseif($bid->auction->current_highest_bidder_id === $bid->user_id && $bid->auction->status === 'ended')
                                    Won
                                @elseif($bid->status === 'outbid')
                                    Outbid
                                @else
                                    {{ ucfirst($bid->status) }}
                                @endif
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bid Placed</label>
                            <p class="text-gray-600">{{ $bid->bid_time->format('M d, Y H:i') }}</p>
                            <p class="text-sm text-gray-500">{{ $bid->bid_time->diffForHumans() }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Auction Status</label>
                            <span class="px-3 py-1 rounded-full text-sm font-medium
                                @if($bid->auction->status === 'active') bg-green-100 text-green-800
                                @elseif($bid->auction->status === 'ended') bg-blue-100 text-blue-800
                                @else bg-gray-100 text-gray-800
                                @endif">
                                {{ ucfirst($bid->auction->status) }}
                            </span>
                        </div>
                        @if($bid->equity_percentage)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Equity Percentage Requested</label>
                            <p class="text-2xl font-bold text-primary">{{ $bid->equity_percentage }}%</p>
                        </div>
                        @endif
                    </div>

                    @if($bid->message)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Investor's Message</label>
                            <div class="p-4 bg-gray-50 rounded-lg">
                                <p class="text-gray-700 italic">"{{ $bid->message }}"</p>
                            </div>
                        </div>
                    @endif

                    <!-- Success/Status Messages -->
                    @if(auth()->user()->isInvestor())
                        @if($bid->auction->status === 'ended' && $bid->auction->current_highest_bidder_id === $bid->user_id)
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
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
                        @elseif($bid->auction->status === 'active' && $bid->auction->current_highest_bidder_id === $bid->user_id)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-blue-800 font-medium">You're currently leading this auction!</p>
                                        <p class="text-blue-700 text-sm">Keep an eye on the auction - other investors might place higher bids.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($bid->status === 'outbid')
                            <div class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-yellow-800 font-medium">Your bid has been outbid.</p>
                                        <p class="text-yellow-700 text-sm">You can place a higher bid if the auction is still active.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Messages for UMKM owners -->
                        @if($bid->auction->status === 'ended' && $bid->auction->current_highest_bidder_id === $bid->user_id)
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-green-800 font-medium">An investor has won your auction!</p>
                                        <p class="text-green-700 text-sm">Please contact {{ $bid->user->name }} to finalize the investment.</p>
                                    </div>
                                </div>
                            </div>
                        @elseif($bid->auction->status === 'active' && $bid->auction->current_highest_bidder_id === $bid->user_id)
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    <div>
                                        <p class="text-blue-800 font-medium">{{ $bid->user->name }} is currently leading your auction!</p>
                                        <p class="text-blue-700 text-sm">The auction is still active and may receive higher bids.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Auction Details -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Auction Details</h3>
                    
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ $bid->auction->title }}</h4>
                        <p class="text-gray-600">{{ $bid->auction->description }}</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <span class="text-sm text-gray-500">Funding Goal</span>
                            <p class="font-semibold">Rp {{ number_format($bid->auction->funding_goal, 0, ',', '.') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Equity Offered</span>
                            <p class="font-semibold">{{ $bid->auction->equity_percentage }}%</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Current Highest Bid</span>
                            <p class="font-semibold">Rp {{ number_format($bid->auction->current_highest_bid ?: $bid->auction->funding_goal, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Investor/Bidder Information -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        @if(auth()->user()->isInvestor())
                            Your Information
                        @else
                            Investor Information
                        @endif
                    </h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium">{{ $bid->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Email</span>
                            <p class="font-medium">{{ $bid->user->email }}</p>
                        </div>
                        @if($bid->user->phone)
                            <div>
                                <span class="text-sm text-gray-500">Phone</span>
                                <p class="font-medium">{{ $bid->user->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Business Information -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Business Information</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Business Name</span>
                            <p class="font-medium">{{ $bid->auction->business->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Category</span>
                            <p class="font-medium">{{ ucfirst($bid->auction->business->category) }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Owner</span>
                            <p class="font-medium">{{ $bid->auction->business->user->name }}</p>
                        </div>
                        @if($bid->auction->business->annual_revenue)
                            <div>
                                <span class="text-sm text-gray-500">Annual Revenue</span>
                                <p class="font-medium">Rp {{ number_format($bid->auction->business->annual_revenue, 0, ',', '.') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Auction Started</span>
                            <p class="font-medium">{{ $bid->auction->start_date->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Bid Placed</span>
                            <p class="font-medium">{{ $bid->bid_time->format('M d, Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Auction {{ $bid->auction->status === 'ended' ? 'Ended' : 'Ends' }}</span>
                            <p class="font-medium">{{ $bid->auction->end_date->format('M d, Y H:i') }}</p>
                            @if($bid->auction->status === 'active')
                                <p class="text-sm text-gray-500">{{ $bid->auction->end_date->diffForHumans() }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Actions</h3>
                    <div class="space-y-3">
                        <a href="{{ route('auctions.show', $bid->auction) }}" 
                           class="btn-primary block text-center transition duration-200">
                            View Full Auction
                        </a>
                        @if(auth()->user()->isInvestor())
                            @if($bid->auction->status === 'active' && $bid->auction->current_highest_bidder_id !== $bid->user_id)
                                <a href="{{ route('auctions.show', $bid->auction) }}#bidForm" 
                                   class="w-full bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-200 text-center block">
                                    Place Higher Bid
                                </a>
                            @endif
                            <a href="{{ route('investor.bids.index') }}" 
                               class="btn-secondary block text-center transition duration-200">
                                Back to My Bids
                            </a>
                        @else
                            <a href="{{ route('umkm.businesses.index') }}" 
                               class="btn-secondary block text-center transition duration-200">
                                Back to My Businesses
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection