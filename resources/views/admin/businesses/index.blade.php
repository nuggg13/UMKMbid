@extends('layouts.app')

@section('title', 'Manage Businesses - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Manage Businesses</h1>
            <p class="text-gray-600 mt-2">Review and approve business registrations</p>
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
                <select id="status-filter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                
                <select id="category-filter" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                    <option value="">All Categories</option>
                    <option value="tech">Technology</option>
                    <option value="fb">F&B</option>
                    <option value="retail">Retail</option>
                    <option value="ecommerce">E-commerce</option>
                    <option value="manufaktur">Manufacturing</option>
                </select>
                
                <input type="text" id="search-input" placeholder="Search businesses..." 
                       class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200">
                
                <button onclick="loadBusinesses(true)" class="btn-primary transition duration-200">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Businesses Table -->
    <div class="card overflow-hidden transition duration-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Business</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="businesses-tbody" class="bg-white divide-y divide-gray-200">
                    @foreach($businesses as $business)
                    <tr id="business-{{ $business->id }}" class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $business->name }}</div>
                                <div class="text-sm text-gray-500">{{ Str::limit($business->description, 50) }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $business->user->name }}</div>
                            <div class="text-sm text-gray-500">{{ $business->user->email }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ ucfirst($business->category) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($business->annual_revenue)
                                Rp {{ number_format($business->annual_revenue, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($business->status === 'approved') bg-green-100 text-green-800
                                @elseif($business->status === 'pending') bg-yellow-100 text-yellow-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst($business->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($business->status === 'pending')
                                <button onclick="approveBusiness({{ $business->id }})" 
                                        class="text-green-600 hover:text-green-900 mr-3 transition duration-200">Approve</button>
                                <button onclick="rejectBusiness({{ $business->id }})" 
                                        class="text-red-600 hover:text-red-900 mr-3 transition duration-200">Reject</button>
                            @endif
                            <a href="{{ route('admin.businesses.show', $business) }}" 
                               class="text-primary hover:text-secondary transition duration-200">View Details</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $businesses->links() }}
    </div>
</div>

<script>
function approveBusiness(businessId) {
    if (!confirm('Are you sure you want to approve this business?')) return;
    
    fetch(`/admin/businesses/${businessId}/approve`, {
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

function rejectBusiness(businessId) {
    if (!confirm('Are you sure you want to reject this business?')) return;
    
    fetch(`/admin/businesses/${businessId}/reject`, {
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