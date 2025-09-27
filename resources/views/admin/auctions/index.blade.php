@extends('layouts.app')

@section('title', 'Manage Auctions - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manage Auctions</h1>
            <p class="text-gray-600 mt-2">Monitor and moderate auction activities</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="btn-secondary transition duration-200">
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Back to Dashboard
        </a>
    </div>

    <!-- Filter and Search -->
    <div class="card mb-6 transition duration-200">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <select id="status-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="ended">Ended</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="pending">Pending</option>
                </select>
                
                <select id="category-filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    <option value="">All Categories</option>
                    <option value="tech">Technology</option>
                    <option value="fb">F&B</option>
                    <option value="retail">Retail</option>
                    <option value="ecommerce">E-commerce</option>
                    <option value="manufaktur">Manufacturing</option>
                </select>
                
                <input type="text" id="search-input" placeholder="Search auctions..." 
                       class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                
                <button onclick="loadAuctions(true)" class="btn-primary transition duration-200">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Auctions Table -->
    <div class="card overflow-hidden transition duration-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Auction</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Funding Goal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equity %</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="auctions-tbody" class="bg-white divide-y divide-gray-200">
                    @foreach($auctions as $auction)
                    <tr id="auction-{{ $auction->id }}" class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $auction->title }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($auction->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $auction->business->name }}</div>
                            <div class="text-sm text-gray-500">{{ $auction->business->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($auction->funding_goal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $auction->equity_percentage }}%
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($auction->status === 'active') bg-green-100 text-green-800
                                @elseif($auction->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($auction->status === 'ended') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($auction->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $auction->end_date->format('M d, Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($auction->status === 'pending')
                                <button onclick="approveAuction({{ $auction->id }})" 
                                        class="text-green-600 hover:text-green-900 mr-3 transition duration-200">Approve</button>
                                <button onclick="rejectAuction({{ $auction->id }})" 
                                        class="text-red-600 hover:text-red-900 transition duration-200">Reject</button>
                            @else
                                <a href="{{ route('auctions.show', $auction) }}" 
                                   class="text-primary hover:text-secondary transition duration-200">View</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $auctions->links() }}
    </div>
</div>

<script>
function approveAuction(auctionId) {
    if (!confirm('Are you sure you want to approve this auction?')) return;
    
    fetch(`/admin/auctions/${auctionId}/approve`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}

function rejectAuction(auctionId) {
    if (!confirm('Are you sure you want to reject this auction?')) return;
    
    fetch(`/admin/auctions/${auctionId}/reject`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection