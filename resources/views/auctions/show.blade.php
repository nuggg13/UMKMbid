@extends('layouts.app')

@section('title', $auction->title . ' - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <div>
            <h1 id="auction-title" class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">{{ $auction->title }}</h1>
            <p id="business-info" class="text-gray-600">{{ $auction->business->name }} • {{ ucfirst($auction->business->category) }}</p>
        </div>
        <span id="auction-status" class="px-4 py-2 rounded-full text-sm font-medium 
            @if($auction->status === 'active') bg-green-100 text-green-800
            @elseif($auction->status === 'ended') bg-gray-100 text-gray-800
            @elseif($auction->status === 'cancelled') bg-red-100 text-red-800
            @else bg-yellow-100 text-yellow-800
            @endif">
            @if($auction->status === 'active') Aktif
            @elseif($auction->status === 'ended') Berakhir
            @elseif($auction->status === 'cancelled') Dibatalkan
            @else Menunggu
            @endif
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-2xl font-bold text-blue-600">Rp {{ number_format($auction->funding_goal, 0, ',', '.') }}</div>
                        <div class="text-sm text-gray-500">Target Modal</div>
                    </div>
                </div>
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-2xl font-bold text-green-600">{{ $auction->equity_percentage }}%</div>
                        <div class="text-sm text-gray-500">Equity Ditawarkan</div>
                    </div>
                </div>
                <div class="card text-center">
                    <div class="card-body">
                        <div class="text-2xl font-bold text-purple-600">{{ $auction->bids->count() }}</div>
                        <div class="text-sm text-gray-500">Total Tawaran</div>
                    </div>
                </div>
            </div>

            <!-- Timer & Action for UMKM Owner -->
            @if(auth()->check() && auth()->user()->id === $auction->business->user_id)
            <div class="card">
                <div class="card-body">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        @if($auction->status !== 'ended')
                        <div class="text-center md:text-left">
                            <div class="text-sm text-gray-500 mb-1">Waktu Tersisa</div>
                            <div id="countdown" class="text-xl font-bold"></div>
                        </div>
                        @endif
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('umkm.auctions.edit', $auction) }}" class="btn-secondary transition duration-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Auction
                            </a>
                            <a href="{{ route('umkm.auctions.bids', $auction) }}" class="btn-primary transition duration-200">
                                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                View Bids ({{ $auction->bids->count() }})
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <!-- Timer for Other Users -->
            @if($auction->status !== 'ended')
            <div class="card">
                <div class="card-body">
                    <div class="flex justify-center">
                        <div class="bg-gray-800 text-white rounded-lg p-4 text-center">
                            <div class="text-sm text-gray-300 mb-1">Waktu Tersisa</div>
                            <div id="countdown" class="text-2xl font-bold"></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @endif

            <!-- Description -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Deskripsi Investasi</h2>
                    <div id="auction-description" class="text-gray-600 whitespace-pre-line">{{ $auction->description }}</div>
                </div>
            </div>

            <!-- Business Details -->
            <div class="card">
                <div class="card-body">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Tentang Bisnis</h2>
                    <div id="business-details" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><span class="font-medium">Kategori:</span> {{ ucfirst($auction->business->category) }}</div>
                            <div><span class="font-medium">Didirikan:</span> {{ $auction->business->established_date ? $auction->business->established_date->format('d M Y') : 'N/A' }}</div>
                            <div><span class="font-medium">Jumlah Karyawan:</span> {{ $auction->business->employee_count ?: 'Not specified' }}</div>
                            @if($auction->business->annual_revenue)
                            <div><span class="font-medium">Pendapatan Tahunan:</span> Rp {{ number_format($auction->business->annual_revenue, 0, ',', '.') }}</div>
                            @endif
                        </div>
                        <div>
                            <span class="font-medium">Deskripsi:</span>
                            <p class="text-gray-600 mt-1">{{ $auction->business->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Terms and Conditions -->
            @if($auction->terms_conditions && count($auction->terms_conditions) > 0)
            <div class="card">
                <div class="card-body">
                    <h2 class="text-xl font-bold text-gray-800 mb-4">Syarat dan Ketentuan</h2>
                    <ul class="list-disc list-inside space-y-2 text-gray-600">
                        @foreach($auction->terms_conditions as $term)
                        <li>{{ $term }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Winner Card (only shown when auction has ended and there's a winner) -->
            @if($auction->status === 'ended' && $auction->bids->where('status', 'won')->first())
            <div class="card border-2 border-green-500">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Pemenang Lelang
                    </h3>
                    @php
                        $winningBid = $auction->bids->where('status', 'won')->first();
                    @endphp
                    <div class="bg-green-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-700">Rp {{ number_format($winningBid->amount, 0, ',', '.') }}</div>
                        <div class="text-gray-700 mt-1">{{ $winningBid->user->name }}</div>
                        <div class="text-sm text-gray-600 mt-2">
                            untuk {{ $winningBid->equity_percentage ?? $auction->equity_percentage }}% equity
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Bid Form (for investors) -->
            @if(auth()->check() && auth()->user()->isInvestor() && $auction->isActive())
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold text-gray-800">Ajukan Tawaran</h3>
                </div>
                <div class="card-body">
                    <form id="bid-form" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tawaran (Rp)</label>
                            <input type="number" id="bid-amount" name="amount" min="{{ $auction->funding_goal }}" step="1000000" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                   placeholder="Masukkan jumlah tawaran" required>
                            <p class="text-sm text-gray-500 mt-1">Minimum: Rp {{ number_format($auction->funding_goal, 0, ',', '.') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Persentase Equity yang Diinginkan (%)</label>
                            <input type="number" id="bid-equity" name="equity_percentage" min="0.01" max="100" step="0.01"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                   placeholder="Masukkan persentase equity">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pesan untuk Pemilik Bisnis (Opsional)</label>
                            <textarea id="bid-message" name="message" rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent transition duration-200"
                                      placeholder="Tulis pesan Anda di sini..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn-primary w-full transition duration-200">
                            Ajukan Tawaran
                        </button>
                    </form>
                    
                    <div id="bid-message" class="mt-4 hidden"></div>
                </div>
            </div>
            @endif

            <!-- Current Highest Bid -->
            <div class="card">
                <div class="card-body">
                    <h3 class="text-lg font-bold text-gray-800 mb-3">Tawaran Tertinggi</h3>
                    @if($auction->current_highest_bid > 0)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-2xl font-bold text-green-600">Rp {{ number_format($auction->current_highest_bid, 0, ',', '.') }}</div>
                        @if($auction->current_highest_bidder)
                        <div class="text-gray-600 mt-1">by {{ $auction->current_highest_bidder->name }}</div>
                        @endif
                    </div>
                    @else
                    <div class="text-gray-500 text-center py-4">Belum ada tawaran</div>
                    @endif
                </div>
            </div>

            <!-- Bid History -->
            <div class="card">
                <div class="card-header">
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Tawaran</h3>
                </div>
                <div class="card-body">
                    <div id="bid-history" class="space-y-3">
                        @if($recentBids->count() > 0)
                            @foreach($recentBids as $bid)
                            <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <div class="font-medium">{{ $bid['user_name'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $bid['bid_time']->format('d M Y H:i') }}</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium">Rp {{ number_format($bid['amount'], 0, ',', '.') }}</div>
                                        {{--
                                        <div class="text-sm text-gray-500">
                                            {{ $loop->index === 0 ? 'Tertinggi' : '' }}
                                        </div>
                                        --}}
                                    </div>
                                </div>
                                @if($bid['message'])
                                <div class="text-sm text-gray-600 mt-1 italic">"{{ $bid['message'] }}"</div>
                                @endif
                            </div>
                            @endforeach
                        @else
                        <div class="text-gray-500 text-center py-4">Belum ada tawaran</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let auctionData = {!! json_encode($auction) !!};
let countdownInterval = null;
// Pass user data to JavaScript
window.user = {!! auth()->check() ? json_encode(['id' => auth()->id(), 'role' => auth()->user()->role]) : 'null' !!};

document.addEventListener('DOMContentLoaded', function() {
    // Start countdown immediately since we have server-side data
    startCountdown(auctionData.end_date);
    
    // Try to load fresh bid history via AJAX (optional enhancement)
    const auctionId = auctionData.id;
    loadBidHistory(auctionId);
    
    // Handle bid form submission
    const bidForm = document.getElementById('bid-form');
    if (bidForm) {
        bidForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const amount = document.getElementById('bid-amount').value;
            const equityPercentage = document.getElementById('bid-equity').value;
            const message = document.getElementById('bid-message').value;
            
            // Validate amount
            if (amount < auctionData.funding_goal) {
                showMessage('Jumlah tawaran harus sama dengan atau lebih dari target modal.', 'error');
                return;
            }
            
            // Submit bid via AJAX
            fetch(`/api/auctions/${auctionId}/bid`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    amount: amount,
                    equity_percentage: equityPercentage,
                    message: message
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showMessage(data.message, 'success');
                    // Reset form
                    bidForm.reset();
                    // Reload bid history
                    loadBidHistory(auctionId);
                    // Update auction data if provided
                    if (data.auction) {
                        auctionData = data.auction;
                        updateAuctionDisplay();
                    }
                } else {
                    showMessage(data.message || 'Gagal mengajukan tawaran.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showMessage('Terjadi kesalahan. Silakan coba lagi.', 'error');
            });
        });
    }
});

function loadBidHistory(auctionId) {
    fetch(`/api/auctions/${auctionId}/bids`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(bids => {
            const historyEl = document.getElementById('bid-history');
            
            if (bids.length === 0) {
                historyEl.innerHTML = '<div class="text-gray-500 text-center py-4">Belum ada tawaran</div>';
                return;
            }
            
            historyEl.innerHTML = bids.map((bid, index) => `
                <div class="border-b border-gray-200 pb-3 last:border-0 last:pb-0">
                    <div class="flex justify-between items-start">
                        <div>
                            <div class="font-medium">${bid.user_name}</div>
                            <div class="text-sm text-gray-500">${new Date(bid.bid_time).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' })}</div>
                        </div>
                        <div class="text-right">
                            <div class="font-medium">Rp ${new Intl.NumberFormat('id-ID').format(bid.amount)}</div>
                            ${(window.user && window.user.role === 'investor' && bid.user_id === window.user.id ? 
                                `<a href="/investor/my-bids/${bid.id}" class="text-primary hover:underline text-sm">View Details</a>` : '')}
                            ${index === 0 ? '<span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Tertinggi</span>' : ''}
                        </div>
                    </div>
                    ${bid.message ? `<div class="text-sm text-gray-600 mt-1 italic">"${bid.message}"</div>` : ''}
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading bid history:', error);
            // Keep server-side rendered content on error
        });
}

function startCountdown(endDate) {
    const endTime = new Date(endDate).getTime();
    
    countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = endTime - now;
        
        if (distance < 0) {
            clearInterval(countdownInterval);
            document.getElementById('countdown').innerHTML = 'LELANG BERAKHIR';
            return;
        }
        
        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        document.getElementById('countdown').innerHTML = 
            `${days}d ${hours}h ${minutes}m ${seconds}s`;
    }, 1000);
}

function updateAuctionDisplay() {
    // Update current highest bid display
    const currentBidEl = document.querySelector('.bg-gray-50 .text-2xl');
    if (currentBidEl && auctionData.current_highest_bid) {
        currentBidEl.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(auctionData.current_highest_bid);
    }
    
    // Update status if changed
    const statusEl = document.getElementById('auction-status');
    if (statusEl) {
        statusEl.className = 'px-4 py-2 rounded-full text-sm font-medium ' +
            (auctionData.status === 'active' ? 'bg-green-100 text-green-800' :
             auctionData.status === 'ended' ? 'bg-gray-100 text-gray-800' :
             auctionData.status === 'cancelled' ? 'bg-red-100 text-red-800' :
             'bg-yellow-100 text-yellow-800');
        statusEl.textContent = 
            auctionData.status === 'active' ? 'Aktif' :
            auctionData.status === 'ended' ? 'Berakhir' :
            auctionData.status === 'cancelled' ? 'Dibatalkan' : 'Menunggu';
    }
}

function showMessage(text, type) {
    const messageEl = document.getElementById('bid-message');
    if (messageEl) {
        messageEl.textContent = text;
        messageEl.className = `mt-4 p-3 rounded-lg text-center ${
            type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
        }`;
        messageEl.classList.remove('hidden');
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            messageEl.classList.add('hidden');
        }, 5000);
    }
}
</script>
@endsection