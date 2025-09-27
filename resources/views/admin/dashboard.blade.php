@extends('layouts.app')

@section('title', 'Admin Dashboard - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">
    <div class="card transition duration-200">
        <div class="card-body">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Admin Dashboard</h1>
            <p class="text-gray-600">Welcome to the UMKMBid administration panel</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Businesses</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ \App\Models\Business::count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Auctions</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ \App\Models\Auction::where('status', 'active')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Users</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ \App\Models\User::count() }}</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Bids</p>
                    <p class="text-xl md:text-2xl font-semibold text-gray-900">{{ \App\Models\Bid::count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Businesses -->
        <div class="card transition duration-200">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Recent Businesses</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach(\App\Models\Business::latest()->take(5)->get() as $business)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $business->name }}</p>
                            <p class="text-xs text-gray-500">{{ $business->category }} • {{ $business->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($business->status === 'approved') bg-green-100 text-green-800
                            @elseif($business->status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            {{ ucfirst($business->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Recent Auctions -->
        <div class="card transition duration-200">
            <div class="card-header">
                <h3 class="text-lg font-medium text-gray-900">Recent Auctions</h3>
            </div>
            <div class="card-body">
                <div class="space-y-4">
                    @foreach(\App\Models\Auction::latest()->take(5)->get() as $auction)
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-900">{{ $auction->title }}</p>
                            <p class="text-xs text-gray-500">{{ $auction->formatted_funding_goal }} • {{ $auction->created_at->diffForHumans() }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs rounded-full
                            @if($auction->status === 'active') bg-green-100 text-green-800
                            @elseif($auction->status === 'draft') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($auction->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card transition duration-200">
        <div class="card-header">
            <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <a href="{{ route('admin.businesses.index') }}" class="action-card transition duration-200">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium text-gray-900">Manage Businesses</p>
                        <p class="text-sm text-gray-500">Review and approve business registrations</p>
                    </div>
                </a>

                <a href="{{ route('admin.auctions.index') }}" class="action-card transition duration-200">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium text-gray-900">Manage Auctions</p>
                        <p class="text-sm text-gray-500">Monitor and moderate auction activities</p>
                    </div>
                </a>

                <a href="{{ route('home') }}" class="action-card transition duration-200">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="font-medium text-gray-900">View Public Site</p>
                        <p class="text-sm text-gray-500">See how the platform looks to users</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection