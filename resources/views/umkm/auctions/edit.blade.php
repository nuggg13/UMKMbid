@extends('layouts.app')

@section('title', 'Edit Auction - ' . $auction->title)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Edit Auction</h1>
            <p class="text-gray-600 mt-2">{{ $auction->title }}</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('umkm.auctions.bids', $auction) }}" class="btn-secondary transition duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                View Bids ({{ $auction->bids->count() }})
            </a>
            <a href="{{ route('umkm.businesses.show', $auction->business) }}" class="btn-secondary transition duration-200">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Business
            </a>
        </div>
    </div>

    <!-- Auction Info -->
    <div class="card mb-6">
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <span class="text-gray-600">Status:</span>
                    <span class="font-medium ml-2 px-2 py-1 rounded-full text-xs
                        @if($auction->status === 'active') bg-green-100 text-green-800
                        @elseif($auction->status === 'ended') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($auction->status) }}
                    </span>
                </div>
                <div>
                    <span class="text-gray-600">Total Bid:</span>
                    <span class="font-medium ml-2">{{ $auction->bids->count() }} bid</span>
                </div>
                <div>
                    <span class="text-gray-600">Mulai:</span>
                    <span class="font-medium ml-2">{{ $auction->start_date->format('d M Y H:i') }}</span>
                </div>
                <div>
                    <span class="text-gray-600">Berakhir:</span>
                    <span class="font-medium ml-2">{{ $auction->end_date->format('d M Y H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- End Auction Section -->
    @if($auction->status === 'active' && $auction->bids->count() > 0)
    <div class="card mb-6 bg-yellow-50 border border-yellow-200">
        <div class="card-body">
            <h3 class="text-lg font-bold text-yellow-800 mb-2">End Auction Early</h3>
            <p class="text-yellow-700 mb-4">You can end this auction early and select a winner from the bids. This action cannot be undone.</p>
            <a href="{{ route('umkm.auctions.bids', $auction) }}" class="btn-primary bg-yellow-600 hover:bg-yellow-700 transition duration-200">
                Select Winner & End Auction
            </a>
        </div>
    </div>
    @endif

    <!-- Valuation Info -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="font-semibold text-gray-800 mb-3">Estimasi Valuasi</h3>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <span class="text-gray-600">Target Modal:</span>
                <span id="display-funding" class="font-medium ml-2">Rp {{ number_format($auction->funding_goal, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="text-gray-600">Equity Ditawarkan:</span>
                <span id="display-equity" class="font-medium ml-2">{{ $auction->equity_percentage }}%</span>
            </div>
            <div class="col-span-2">
                <span class="text-gray-600">Estimasi Valuasi Bisnis:</span>
                <span id="display-valuation" class="font-medium ml-2 text-lg text-primary">Rp {{ number_format($auction->funding_goal / ($auction->equity_percentage / 100), 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="error-message" class="hidden bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg"></div>

    <!-- Success Message -->
    <div id="success-message" class="hidden bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"></div>

    <!-- Action Buttons -->
    <div class="pt-6 flex space-x-4">
        @if($auction->bids->count() == 0)
            <button type="submit" 
                    class="btn-primary transition duration-200"
                    onclick="submitForm()">
                Update Auction
            </button>
            <button type="button" 
                    class="btn-danger transition duration-200"
                    onclick="cancelAuction()">
                Cancel Auction
            </button>
        @else
            <div class="text-gray-500 italic">
                Auction cannot be edited after bids have been placed
            </div>
        @endif
        <a href="{{ route('umkm.businesses.show', $auction->business) }}" 
           class="btn-secondary transition duration-200">
            Back to Business
        </a>
    </div>
</div>

<script>
function submitForm() {
    const form = document.querySelector('form');
    const formData = new FormData(form);
    
    fetch('{{ route("umkm.auctions.update", $auction) }}', {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('success-message').classList.remove('hidden');
            document.getElementById('success-message').textContent = data.message;
            document.getElementById('error-message').classList.add('hidden');
        } else {
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-message').textContent = data.errors ? Object.values(data.errors).join(', ') : data.message;
            document.getElementById('success-message').classList.add('hidden');
        }
    })
    .catch(error => {
        document.getElementById('error-message').classList.remove('hidden');
        document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
        document.getElementById('success-message').classList.add('hidden');
    });
}

function cancelAuction() {
    if (!confirm('Are you sure you want to cancel this auction? This action cannot be undone.')) {
        return;
    }
    
    fetch('{{ route("umkm.auctions.destroy", $auction) }}', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = '{{ route("umkm.businesses.show", $auction->business) }}';
        } else {
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-message').textContent = data.message;
            document.getElementById('success-message').classList.add('hidden');
        }
    })
    .catch(error => {
        document.getElementById('error-message').classList.remove('hidden');
        document.getElementById('error-message').textContent = 'An error occurred. Please try again.';
        document.getElementById('success-message').classList.add('hidden');
    });
}
</script>
@endsection