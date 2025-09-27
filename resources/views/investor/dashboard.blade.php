@extends('layouts.app')

@section('title', 'Investor Dashboard - UMKMBid')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="card mb-8 transition duration-200">
        <div class="card-body">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
            <p class="text-gray-600">Temukan peluang investasi terbaik dari UMKM yang sedang berkembang.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700" id="total-auctions">0</p>
                    <p class="text-gray-500">Total Lelang</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700" id="my-bids">0</p>
                    <p class="text-gray-500">Tawaran Saya</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-yellow-100 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700" id="active-bids">0</p>
                    <p class="text-gray-500">Tawaran Aktif</p>
                </div>
            </div>
        </div>

        <div class="stat-card transition duration-200">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 0h10a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-xl md:text-2xl font-bold text-gray-700">Rp <span id="total-invested">0</span></p>
                    <p class="text-gray-500">Total Investasi</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-8 transition duration-200">
        <div class="card-header">
            <h2 class="text-xl font-bold text-gray-800">Aksi Cepat</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('auctions.index') }}" class="action-card transition duration-200">
                    <div class="bg-primary/10 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Cari Lelang</h3>
                </a>
                <a href="{{ route('investor.bids.index') }}" class="action-card transition duration-200">
                    <div class="bg-blue-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Tawaran Saya</h3>
                </a>
                <a href="{{ route('auctions.index') }}?filter=trending" class="action-card transition duration-200">
                    <div class="bg-green-100 p-3 rounded-full w-12 h-12 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="font-medium text-gray-800">Lelang Trending</h3>
                </a>
            </div>
        </div>
    </div>

    <!-- Active Auctions -->
    <div class="card mb-8 transition duration-200">
        <div class="card-header">
            <h2 class="text-xl font-bold text-gray-800">Lelang Aktif</h2>
        </div>
        <div class="card-body">
            <div id="active-auctions-list">
                <p class="text-gray-500 text-center py-8">Memuat lelang aktif...</p>
            </div>
        </div>
    </div>

    <!-- My Recent Bids -->
    <div class="card transition duration-200">
        <div class="card-header">
            <h2 class="text-xl font-bold text-gray-800">Tawaran Terbaru Saya</h2>
        </div>
        <div class="card-body">
            <div id="recent-bids">
                <p class="text-gray-500 text-center py-8">Belum ada tawaran</p>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load dashboard statistics
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-auctions').textContent = data.total_auctions || 0;
            document.getElementById('my-bids').textContent = data.my_bids || 0;
            document.getElementById('active-bids').textContent = data.my_active_bids || 0;
            
            // Format currency for total invested
            const totalInvested = data.total_invested || 0;
            document.getElementById('total-invested').textContent = new Intl.NumberFormat('id-ID').format(totalInvested);
        })
        .catch(error => {
            console.error('Error loading dashboard stats:', error);
        });

    // Load active auctions (simplified version)
    loadActiveAuctions();
    
    // Load recent bids
    loadRecentBids();
});

function loadActiveAuctions() {
    fetch('/api/auctions')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('active-auctions-list');
            // Check if data is paginated (has data property) or is direct array
            const auctions = data.data || data;
            
            if (auctions.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-8">Tidak ada lelang aktif saat ini</p>';
                return;
            }
            
            container.innerHTML = auctions.slice(0, 3).map(auction => `
                <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition duration-200">
                    <h3 class="font-bold text-lg mb-2">${auction.title}</h3>
                    <p class="text-gray-600 mb-2">${auction.description}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-500">Target: Rp ${new Intl.NumberFormat('id-ID').format(auction.funding_goal)}</span>
                        <a href="/auctions/${auction.id}" class="btn-primary text-sm transition duration-200">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading active auctions:', error);
            document.getElementById('active-auctions-list').innerHTML = '<p class="text-red-500 text-center py-8">Gagal memuat lelang aktif</p>';
        });
}

function loadRecentBids() {
    fetch('/api/my-recent-bids')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('recent-bids');
            if (data.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-8">Belum ada tawaran</p>';
                return;
            }
            
            container.innerHTML = data.slice(0, 5).map(bid => `
                <div class="border-b border-gray-200 py-3 last:border-b-0">
                    <div class="flex justify-between items-center">
                        <div>
                            <h4 class="font-semibold">${bid.auction_title}</h4>
                            <p class="text-sm text-gray-600">Tawaran: Rp ${new Intl.NumberFormat('id-ID').format(bid.amount)}${bid.equity_percentage ? ` (${bid.equity_percentage}% equity)` : ''}</p>
                        </div>
                        <div class="flex flex-col items-end">
                            <span class="text-sm text-gray-500">${new Date(bid.created_at).toLocaleDateString('id-ID')}</span>
                            <a href="/investor/my-bids/${bid.id}" class="text-primary hover:underline text-sm mt-1">View Details</a>
                        </div>
                    </div>
                </div>
            `).join('');
        })
        .catch(error => {
            console.error('Error loading recent bids:', error);
            document.getElementById('recent-bids').innerHTML = '<p class="text-red-500 text-center py-8">Gagal memuat tawaran terbaru</p>';
        });
}
</script>
@endsection