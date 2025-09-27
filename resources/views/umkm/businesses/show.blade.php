@extends('layouts.app')

@section('title', $business->name . ' - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">{{ $business->name }}</h1>
            <p class="text-gray-600 mt-2">{{ ucfirst($business->category) }} • Established {{ $business->established_date ? $business->established_date->format('Y') : 'N/A' }}</p>
        </div>
        <div class="flex flex-wrap gap-4">
            @if(auth()->user()->id === $business->user_id)
                <a href="{{ route('umkm.businesses.edit', $business) }}" class="btn-secondary transition duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Business
                </a>
                <a href="{{ route('umkm.auctions.create', $business) }}" class="btn-primary transition duration-200">
                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Create Auction
                </a>
            @endif
            <a href="{{ route('umkm.businesses.index') }}" class="btn-secondary transition duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Businesses
            </a>
        </div>
    </div>

    <!-- Business Status -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Business Status</h3>
                    <p class="text-gray-600 mt-1">Current approval status from admin</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-medium
                    @if($business->status === 'approved') bg-green-100 text-green-800
                    @elseif($business->status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    {{ ucfirst($business->status) }}
                </span>
            </div>
            @if($business->status === 'pending')
                <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-yellow-800">Your business is pending admin approval. You'll be notified once it's reviewed.</p>
                </div>
            @elseif($business->status === 'rejected')
                <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-800">Your business registration was rejected. Please contact support for more information.</p>
                </div>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Business Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-xl font-bold text-gray-800 mb-4">Business Information</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <p class="text-gray-600">{{ $business->description }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                                <p class="text-gray-600">{{ ucfirst($business->category) }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Employee Count</label>
                                <p class="text-gray-600">{{ $business->employee_count ?: 'Not specified' }} employees</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Annual Revenue</label>
                                <p class="text-gray-600">
                                    @if($business->annual_revenue)
                                        Rp {{ number_format($business->annual_revenue, 0, ',', '.') }}
                                    @else
                                        Not disclosed
                                    @endif
                                </p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Business License</label>
                                <p class="text-gray-600">{{ $business->business_license ?: 'Not provided' }}</p>
                            </div>
                        </div>
                        
                        @if($business->address)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                            <p class="text-gray-600">{{ $business->address }}</p>
                        </div>
                        @endif
                        
                        @if($business->website || $business->social_media)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @if($business->website)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                <a href="{{ $business->website }}" target="_blank" class="text-primary hover:text-secondary font-medium transition duration-200">{{ $business->website }}</a>
                            </div>
                            @endif
                            @if($business->social_media)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Social Media</label>
                                <p class="text-gray-600">{{ $business->social_media }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Auctions -->
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Investment Auctions</h3>
                        @if(auth()->user()->id === $business->user_id && $business->status === 'approved')
                            <a href="{{ route('umkm.auctions.create', $business) }}" class="btn-primary transition duration-200">
                                Create New Auction
                            </a>
                        @endif
                    </div>

                    @if($business->auctions->count() > 0)
                        <div class="space-y-4">
                            @foreach($business->auctions as $auction)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition duration-200">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-lg font-semibold text-gray-800">{{ $auction->title }}</h4>
                                        <span class="px-3 py-1 rounded-full text-sm font-medium
                                            @if($auction->status === 'active') bg-green-100 text-green-800
                                            @elseif($auction->status === 'ended') bg-blue-100 text-blue-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ ucfirst($auction->status) }}
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 mb-3">{{ Str::limit($auction->description, 150) }}</p>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-3">
                                        <div>
                                            <span class="text-sm text-gray-500">Funding Goal</span>
                                            <p class="font-semibold">Rp {{ number_format($auction->funding_goal, 0, ',', '.') }}</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Equity Offered</span>
                                            <p class="font-semibold">{{ $auction->equity_percentage }}%</p>
                                        </div>
                                        <div>
                                            <span class="text-sm text-gray-500">Total Bids</span>
                                            <p class="font-semibold">{{ $auction->bids->count() }} bids</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-500">
                                            Ends: {{ $auction->end_date->format('M d, Y H:i') }}
                                        </span>
                                        <div class="flex gap-2">
                                            <a href="{{ route('auctions.show', $auction) }}" class="text-primary hover:text-secondary font-medium transition duration-200">
                                                View Details
                                            </a>
                                            @if(auth()->user()->id === $business->user_id)
                                                <a href="{{ route('umkm.auctions.bids', $auction) }}" class="text-green-600 hover:text-green-800 font-medium transition duration-200">
                                                    View Bids ({{ $auction->bids->count() }})
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h4 class="text-lg font-medium text-gray-600 mb-2">No Auctions Yet</h4>
                            <p class="text-gray-500 mb-4">Create your first investment auction to start raising funds.</p>
                            @if(auth()->user()->id === $business->user_id && $business->status === 'approved')
                                <a href="{{ route('umkm.auctions.create', $business) }}" class="btn-primary transition duration-200">
                                    Create First Auction
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Owner Information -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Business Owner</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm text-gray-500">Name</span>
                            <p class="font-medium">{{ $business->user->name }}</p>
                        </div>
                        <div>
                            <span class="text-sm text-gray-500">Email</span>
                            <p class="font-medium">{{ $business->user->email }}</p>
                        </div>
                        @if($business->user->phone)
                            <div>
                                <span class="text-sm text-gray-500">Phone</span>
                                <p class="font-medium">{{ $business->user->phone }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Quick Stats</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Auctions</span>
                            <span class="font-semibold">{{ $business->auctions->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Active Auctions</span>
                            <span class="font-semibold">{{ $business->auctions->where('status', 'active')->count() }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Bids Received</span>
                            <span class="font-semibold">{{ $business->auctions->sum(function($auction) { return $auction->bids->count(); }) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Member Since</span>
                            <span class="font-semibold">{{ $business->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection